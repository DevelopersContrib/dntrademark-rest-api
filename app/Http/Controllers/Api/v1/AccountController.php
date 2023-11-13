<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (Hash::check($request->current_password, $user->password)) {
                $status = User::find($user->id)->update(['password' => Hash::make($request->new_password)]);

                return response()->json([
                    'success' => $status,
                    'message' => 'New password saved.'
                ], JsonResponse::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Incorrect password.'
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            $user->delete();
            auth()->logout();

            return response()->json([], JsonResponse::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            //throw $th;/
        }
    }
}
