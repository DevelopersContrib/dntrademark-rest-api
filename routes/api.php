<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureApiKeyIsValid;

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\PackageController;

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

Route::middleware(EnsureApiKeyIsValid::class)->prefix('v1')->group(function() {
	Route::prefix('auth')->group(function() {
		Route::post('login',[ AuthController::class, 'login' ]);
	});

	Route::prefix('users')->group(function() {
		Route::get('/',[ UserController::class, 'index' ]);
	});

	Route::prefix('user')->group(function() {
		Route::get('check',[ UserController::class, 'checkEmail' ]);
		Route::post('save',[ UserController::class, 'storeUser' ]);
	});

	Route::prefix('packages')->group(function() {
		Route::get('/', [ PackageController::class, 'index' ]);
	});
});