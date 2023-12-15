<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
// use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Mail\VerificationMail;

use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
  //
  public function index()
  {
    $users = User::all();

    return response()->json([
      'status' => true,
      'users' => $users
    ], 200);
  }

  public function show($id)
  {
    try {
      $user = User::with('package')->find($id);

      return response()->json([
        'success' => true,
        'user' => new UserResource($user)
      ], JsonResponse::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage()
      ], JsonResponse::HTTP_ACCEPTED);
    }
  }

  public function storeUser(StoreUserRequest $request)
  {

    $user = new User();
    $user->first_name = $request->input('first_name');
    $user->last_name = $request->input('last_name');
    $user->email = $request->input('email');
    $user->password = Hash::make($request->input('password'));

    if ($user->save()) {
      if (!$user->package_id) {
        $user->package_id = 1;
        $user->save();
      }

      $user->verification_code = Str::random(64);
      Auth::login($user);

      $token = $user->createToken('api-token')->plainTextToken;
      // $this->sendVerificationEmail($user);

      //Call create invoice vnoc.
      InvoiceController::create($user->id);
      LeadController::saveLead($user->email, "dntrademark.com");

      return response()->json([
        'success' => true,
        'user' => $user,
        'token' => $token,
      ], JsonResponse::HTTP_OK);
    }
  }

  public function updateUser(UpdateUserRequest $request): JsonResponse
  {
    try {
      $user = $request->user();
      $data = $request->validated();
      $data = array_filter($data);

      if (isset($request->allow_email) && isset($request->allow_sms)) {
        $data['allow_email'] = $request->allow_email;
        $data['allow_sms'] = $request->allow_sms;
      }

      if (isset($request->package_id) && $request->package_id !== 1) {
        $currentDate = Carbon::now();
        $expiryDate = $currentDate->addMonth();

        $data['package_expiry'] = $expiryDate->toDateString();
      }

      $user->update($data);

      return response()->json([
        'success' => true,
        'user' => $user,
        'data' => $data
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage()
      ]);
    }
  }

  public function checkEmail(Request $request)
  {
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

    if (empty($user)) {
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

  public function checkCredentials(LoginRequest $request)
  {
    try {
      $user = User::where('email', $request->input('email'))->first();

      if (!$user) {
        return response()->json([
          'success' => false,
          'error' => 'Email not found.'
        ], 200);
      } else {
        if (Hash::check($request->input('password'), $user->password)) {
          return response()->json([
            'success' => true,
            'error' => ''
          ], 200);
        } else {
          return response()->json([
            'success' => false,
            'error' => 'Incorrect password.'
          ], 200);
        }
      }
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function sendVerificationEmail($user)
  {
    $email = $user->email;

    $user = User::where('email', $email)->first();

    if (empty($user->verification_code)) {
      $user->verification_code = Str::random(64);

      $user->update();
    }

    $data = [
      'name' => ucwords($user->first_name),
      'verification_link' => 'https://dash.dntrademark.com/auth/verify/' . $user->verification_code
    ];

    $status = Mail::to($user->email)->send(new VerificationMail($data));

    return $status;
  }
}
