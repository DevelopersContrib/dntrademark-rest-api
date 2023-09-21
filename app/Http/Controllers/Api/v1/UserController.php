<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
  //
  public function index($apiKey) {
    if(!$this->validateApiKey($apiKey)) {
		return response()->json([
			'status' => FALSE,
			'error' => 'Invalid API Key.'
		],200);
    }
    $users = User::all();

    return response()->json([
		'status' => TRUE,
		'users' => $users
    ], 200);
  }

  public function checkEmail(Request $request, $apiKey) {
    if(!$this->validateApiKey($apiKey)) {
      return response()->json([
        'status' => FALSE,
        'error' => 'Invalid API Key.'
      ],200);
    }

    $request->validate([
      'email' => 'required|email'
    ]);

    $user = User::where('email',$request->email)->first();

    if(empty($user)) {
      return response()->json([
        'success' => TRUE,
        'data' => [
          'data' => [],
          'success' => FALSE,
          'error' => 'Email is available.'
        ],

      ],200);
    } else {
      $data = [
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
      ];

      return response()->json([
        'success' => TRUE,
        'data' => [
          'data' => $data,
          'success' => TRUE,
          'error' => ''
        ],

      ],200);
    }
  }

  private function validateApiKey($apiKey) {
    $status = FALSE;
    
    if($apiKey == md5('dntrademark.com')) {
      $status = TRUE;
    }

    return $status;
  }
}