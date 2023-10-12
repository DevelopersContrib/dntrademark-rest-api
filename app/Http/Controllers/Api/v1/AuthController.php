<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use Hash;

class AuthController extends Controller
{
	//
	public function login(Request $request)
	{
		try {
			$credentials = $request->only('email', 'password');

			if (Auth::attempt($credentials)) {
				$user = $request->user();
				$token = $user->createToken('auth-token')->plainTextToken;

				return response()->json([
					'success' => true,
					'data' => [
						'user' => $user,
						'token' => $token,
					]
				], 200);
			}

			return response()->json(['message' => 'Unauthorized'], 401);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
