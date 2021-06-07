<?php

namespace App\Http\Controllers\Operations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Token as AutorizationToken;
use App\Models\Wallet;
use App\Models\Transaction;

class TransactionController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function pay(Request $request)
	{
		$status = 'error';
		$message = 'pay_fail';
		$code = 500;

		$user = $request->user();
		$wallet = Wallet::where('user_id', $user->id)
			->first();
		$receiver = User::where('email', $request->email)
			#->where('phone', $request->phone)
			#->where('document', $request->document)
			->first();

		$token = AutorizationToken::where('user_id', $user->id)
			->where('status', 'ACTIVE')
			->first();

		if ($token->key == $request->code) {
			if ($receiver) {
				if ($wallet->mount >= $request->mount) {
					# add transaction
					$transaction = Transaction::create([
						'user_id'				=> $user->id,
						'receiver_id'		=> $receiver->id,
						'mount'					=> $request->mount
					]);
					$transaction->type = 'PAYMENT';
					$transaction->save();

					# update balance
					$this->updateBalance($user->id, $transaction->mount, false);
					$this->updateBalance($receiver->id, $transaction->mount, true);

					$code = 200;
					$status = 'success';
					$message = 'pay_success';
					AutorizationToken::where('user_id', $user->id)->update(['status' => 'INACTIVE']);
				} else {
					$message = 'insufficient_balance';
				}
			} else {
				$message = 'receiver_not_found';
			}
		} else {
			$message = 'code_fail';
		}

		return response()->json([
			'status' => $status,
			'message' => $message
		], $code);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function recharge(Request $request)
	{
		$status = 'error';
		$message = 'recharge_fail';
		$code = 500;

		$user = $request->user();

		if ($user->document === $request->document && $user->phone === $request->phone) {
			# add transaction
			$transaction = Transaction::create([
				'user_id'				=> $user->id,
				'mount'					=> $request->mount,
				'type'					=> 'RECHARGE'
			]);

			$code = 200;
			$status = 'success';
			$message = 'request_success';
		} else {
			$message = 'params_fail';
		}

		return response()->json([
			'status' => $status,
			'message' => $message
		], $code);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function search(Request $request)
	{
		$status = 'error';
		$message = 'user_not_found';
		$code = 500;
		$data = null;

		$user = User::where('document', $request->document)
			->where('phone', $request->phone)
			->first();

		if ($user) {
			$user->wallet = Wallet::where('user_id', $user->id)->first();
			$data = $user;

			$code = 200;
			$status = 'success';
			$message = 'user_found';
		}

		return response()->json([
			'status' => $status,
			'message' => $message,
			'data' => $data
		], $code);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function checkTransaction(Request $request)
	{
		$status = 'error';
		$message = 'transaction_not_found';
		$code = 500;
		$data = array();

		$transaction = Transaction::where('type', 'RECHARGE')
			->where('status', 'SENDED')
			->where('id', $request->id)
			->first();

		if ($transaction) {
			$transaction->status = $request->method == 'acept' ? 'ACEPTED' : 'REJECTED';
			$transaction->save();

			if ($request->method == 'acept') {
				# update balance
				$this->updateBalance($transaction->user_id, $transaction->mount, true);

				$message = 'transaction_acepted';
			} else {
				$message = 'transaction_rejected';
			}

			$transactions = Transaction::select('transactions.*', 'users.name')
				->join('users', 'users.id', 'user_id')
				->where('transactions.status', 'SENDED')
				->where('transactions.type', 'RECHARGE')
				->orderBy('transactions.created_at', 'desc')
				->get();

			$history = Transaction::select('transactions.*', 'users.name')
				->join('users', 'users.id', 'user_id')
				->where('transactions.status', '<>', 'SENDED')
				->where('transactions.type', 'RECHARGE')
				->orderBy('transactions.updated_at', 'desc')
				->get();

			$data = array('transactions' => $transactions, 'history' => $history);

			$status = 'success';
			$code = 200;
		}

		return response()->json([
			'status' => $status,
			'message' => $message,
			'data' => $data
		], $code);
	}

	public function updateBalance($userId, $mount, $method)
	{
		$wallet = Wallet::where('user_id', $userId)
			->first();

		if ($method) {
			$wallet->mount += $mount;
		} else {
			$wallet->mount -= $mount;
		}

		$wallet->updated_at = now();
		$wallet->save();
	}
}
