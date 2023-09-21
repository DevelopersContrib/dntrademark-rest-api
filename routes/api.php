<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureApiKeyIsValid;

use App\Http\Controllers\Api\v1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::prefix('v1')->namespace('v1')->group(function() {
	Route::prefix('dntrademark')->group(function() {
		Route::get('/',[ UserController::class, 'index' ])->middleware(EnsureApiKeyIsValid::class);

		Route::prefix('user')->group(function() {
			Route::get('check',[ UserController::class, 'checkEmail' ])->middleware(EnsureApiKeyIsValid::class);
			Route::post('save',[ UserController::class, 'storeUser' ])->middleware(EnsureApiKeyIsValid::class);
		});
	});
});
