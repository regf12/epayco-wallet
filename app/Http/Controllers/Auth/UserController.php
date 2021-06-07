<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Wallet;

class UserController extends Controller
{
	/**
	 * Get authenticated user.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function current(Request $request)
	{
			return response()->json($request->user());
	}

	public function getUserDetail(Request $request)
	{
		$status = 'error';
		$message = 'user_not_found';
		$code = 500;
		
		$user = User::where('document', $request->document)->where('phone', $request->phone)->first();
		
		if ($user) {
			$wallet = Wallet::where('user_id', $user->id)->first();
			
			$user['wallet'] = $wallet ? $wallet : array('mount' => 0);
		
			$code = 200;
			$status = 'success';
			$message = 'user_found';
		}

		return response()->json([
			'status' => $status,
			'message' => $message,
			'data' => $status == 'success' ? $user : null
		], $code);
	}
}
