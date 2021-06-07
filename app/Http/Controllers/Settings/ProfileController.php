<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Token as AutorizationToken;

class ProfileController extends Controller
{
	/**
	 * Update the user's profile information.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$user = $request->user();

		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users,email,' . $user->id,
		]);

		return tap($user)->update($request->only('name', 'email'));
	}

	public function getProfile(Request $request)
	{
		$user = $request->user();

		if ($user->type == 'ADMIN') {

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

			return response()->json([
				'status' => 'success',
				'data' => array('transactions' => $transactions, 'history' => $history)
			], 200);
			
		} else {

			$history = Transaction::where('user_id', $user->id)
				->orWhere('receiver_id', $user->id)
				->orderBy('updated_at', 'asc')
				->get();

			$balance = Wallet::select('mount')->where('user_id', $user->id)->limit(1)->get()[0]->mount + 0;

			return response()->json([
				'status' => 'success',
				'data' => array('balance' => $balance, 'history' => $history)
			], 200);
		}
	}

	public function email() {
		return view("email");
	}

	public function code(Request $request)
	{
		$status = 'error';
		$message = 'code_sent_failed';
		$code = 500;

		$user = $request->user();

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$confirmationCode = substr(str_shuffle($permitted_chars), 0, 6);
		#$encriptCode = $confirmationCode;

		AutorizationToken::where('user_id', $user->id)->update(['status' => 'INACTIVE']);
		AutorizationToken::create([
			'user_id'	=> $user->id,
			'key'	=> $confirmationCode
		]);

		###############################

    $mail = new PHPMailer(true);
		$mail->IsHTML(true);
		# $mail->SMTPDebug  = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';
		
		$mail->SMTPAuth = true; // authentication enabled
		$mail->Username = "regf.12@gmail.com";
		$mail->Password = "qkbcfiowsqfrolxo";
		
		# Recipient
		$mail->SetFrom("regf.12@gmail.com", 'Epayco Wallet');
		$mail->isHTML = true;
		$mail->Subject = "Test Subject";

		$mail->AddAddress("$user->email", "$user->name");
		$mail->Body = $this->htmlMail($confirmationCode);
		
		if ($mail->Send()) {
			$status = 'success';
			$message = 'code_sent';
			$code = 200;
		}

		return response()->json([
			'status' => $status,
			'message' => $message
		], $code);
	}

	public function htmlMail($code)
	{
		$html = "
		<div class='container'>
			<div class='col-12 col-md-6'>
				<div class='w-100 h-100 d-flex justify-content-center align-items-center flex-column'>
					<div class='text-center p-3'>
						Confirmation code:
					</div>
					<hr>
					<div class='text-center p-3'>
						$code
					</div>
				</div>
			</div>
		</container>
		";
		return $html;
	}
}
