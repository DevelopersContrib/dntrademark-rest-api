<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Str;

use App\Models\User;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginRequest;

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
    // $user = User::create([
    //   'first_name' => $request->input('first_name'),
    //   'last_name' => $request->input('last_name'),
    //   'email' => $request->input('email'),
    //   'password' => Hash::make($request->input('password')),
    // ]);

    $user = new User();
    $user->first_name = $request->input('first_name');
    $user->last_name = $request->input('last_name');
    $user->email = $request->input('email');
    $user->password = Hash::make($request->input('password'));
    $user->verification_code = Str::random(64);

    if($user->save()) {
      $this->sendVerificationEmail($user);
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

  public function checkEmail($user) {
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

  public function checkCredentials(LoginRequest $request) {
    try {
      $user = User::where('email', $request->input('email'))->first();

      if(!$user) {
        return response()->json([
          'success' => false,
          'error' => 'Email not found.'
        ], 404);
      } else {
        if(Hash::check($request->input('password'), $user->password)) {
          return response()->json([
            'success' => true,
            'error' => ''
          ], 200);
        } else {
          return response()->json([
            'success' => false,
            'error' => 'Incorrect password.'
          ], 422);
        }
      }
      return response()->json([
        'success' => $count,
      ], 200);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function sendVerificationEmail($user) {
    $email = $user->email;

    $user = User::where('email',$email)->first();

    if(empty($user->verification_code)) {
      $user->verification_code = Str::random(64);

      $user->update();
    }

    $data = [
      'name' => ucwords($user->first_name),
      'verification_link' => 'https://dash.dntrademark.com/auth/verify/'.$user->verification_code
    ];

    Mail::to($user->email)->later(now()->addMinutes(1), new EmailVerificationMail($data));

    return response()->json([
      'status'=>TRUE
    ],200); 
  }
}