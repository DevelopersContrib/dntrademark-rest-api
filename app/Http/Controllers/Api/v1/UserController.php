<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

use App\Http\Requests\StoreUserRequest;

use Hash;

class UserController extends Controller
{
  //
  public function index() {
    $users = User::all();

    return response()->json([
      'status' => true,
      'users' => $users
    ], 200);
  }

  public function storeUser(StoreUserRequest $request) {
    $user = User::create([
      'first_name' => $request->input('first_name'),
      'last_name' => $request->input('last_name'),
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('password')),
    ]);

    if($user) {
      return response()->json([
        'success' => true,
        'data' => [
          'data' => [
            'id' => $user->id
          ],
          'success' => true,
          'error' => ''
        ],
      ], 200);
    }
  }

  public function checkEmail(Request $request) {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'data' => [],
        'errors' => $validator->errors(),
      ], 422); // 422 is the status code for Unprocessable Entity
    }

    $user = User::where('email', $request->input('email'))->first();

    if(empty($user)) {
      return response()->json([
        'success' => true,
        'data' => [
          'data' => [],
          'success' => false,
          'error' => 'Email is available.'
        ],

      ], 200);
    } else {
      $data = [
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
      ];

      return response()->json([
        'success' => true,
        'data' => [
          'data' => $data,
          'success' => true,
          'error' => ''
        ],

      ], 200);
    }
  }
}