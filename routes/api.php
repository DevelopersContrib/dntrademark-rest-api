<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureApiKeyIsValid;

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\DomainController;
use App\Http\Controllers\Api\v1\DomainItemController;
use App\Http\Controllers\Api\v1\NotificationController;
use App\Http\Controllers\Api\v1\PackageController;
use App\Http\Controllers\Api\v1\PaymentController;
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

Route::middleware(EnsureApiKeyIsValid::class)->prefix('v1')->group(function () {
	Route::prefix('auth')->group(function () {
		Route::post('login', [AuthController::class, 'login'])->name('login');
	});

	//Public API endpoints
	Route::prefix('users')->group(function () {
		Route::get('/', [UserController::class, 'index']);
	});

	Route::prefix('user')->group(function () {
		Route::post('save', [UserController::class, 'storeUser']);
		Route::get('check', [UserController::class, 'checkEmail']);
		Route::post('check/credentials', [UserController::class, 'checkCredentials']);
	});

	Route::prefix('packages')->group(function () {
		Route::get('/', [PackageController::class, 'index']);
	});

	Route::prefix('package')->group(function () {
		Route::get('{id}', [PackageController::class, 'getPackageById']);
	});
	//End of public API endpoints

	Route::middleware('auth:sanctum')->group(function () {
		//logout
		Route::post('auth/logout', [AuthController::class, 'destroy'])->name('logout');

		//Payment
		Route::prefix('payment')->group(function () {
			Route::post('charge', [PaymentController::class, 'createCharge']);
		});

		//User
		Route::prefix('user')->group(function () {
			Route::get('/{id}', [UserController::class, 'show']);
			Route::put('update', [UserController::class, 'updateUser']);
		});

		//Domain
		Route::prefix('domains')->group(function () {
			Route::get('/', [DomainController::class, 'index']);
			Route::post('add', [DomainController::class, 'storeDomains']);
			Route::get('count', [DomainController::class, 'countDomains']);
			Route::get('count/hits', [DomainController::class, 'countHitsDomain']);
			Route::get('count/no-hits', [DomainController::class, 'countNoHitsDomains']);
			Route::get('risks', [DomainController::class, 'getDomainsAtRisk']);
			Route::get('stats', [DomainController::class, 'domainStats']);
			Route::get('hits', [DomainController::class, 'getDomainsWithHits']);
			Route::get('no-hits', [DomainController::class, 'getDomainsWithoutHits']);
			Route::get('/items/{domainId}', [DomainController::class, 'getDomainItems']);
			Route::delete('delete', [DomainController::class, 'destroy']);
		});

		//Domain Item
		Route::prefix('item')->group(function () {
			Route::post('/{domain:domain_name}', [DomainItemController::class, 'store']);
		});

		//Account
		Route::prefix('account')->group(function () {
			Route::post('delete', [AccountController::class, 'destroy']);
			Route::put('reset-password', [AccountController::class, 'reset']);
		});

		//Notifications
		Route::prefix('notifications')->group(function () {
			Route::get('/', [AccountController::class, 'getAllNotifications']);
			Route::get('/{id}', [AccountController::class, 'getNotification']);
		});

		//Items
		Route::prefix('items')->group(function () {
			Route::get('/owner/{itemId}', [DomainItemController::class, 'getItemOwner']);
		});
	}); //sanctum
});
