<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
	public function __construct()
	{
		Stripe::setApiKey(env('STRIPE_KEY_TEST'));
	}

	public function createCharge(Request $request)
	{
		return response()->json([
			'success' => false,
			'STRIPE_KEY_TEST' => env('STRIPE_KEY_TEST')
		], JsonResponse::HTTP_ACCEPTED);
		// try {
		// 	$intent = PaymentIntent::create([
		// 		'amount' => $request->amount, // Amount in cents
		// 		'currency' => 'usd',
		// 		'payment_method' => 'pm_card_visa', // Payment method ID (you can obtain this from the frontend)
		// 		'confirmation_method' => 'manual',
		// 		'confirm' => true,
		// 	]);

		// 	return response()->json([
		// 		'success' => true,
		// 		'request' => $request->all(),
		// 		'stripe' => $intent
		// 	], JsonResponse::HTTP_OK);

		// 	// return response()->json(['client_secret' => $intent->client_secret]);
		// } catch (\Exception $e) {
		// 	return response()->json([
		// 		'success' => false,
		// 		'message' => $e->getMessage()
		// 	], JsonResponse::HTTP_ACCEPTED);
		// }
	}

	public function createCheckoutSession(Request $request)
	{
		Stripe::setApiKey('your_secret_key');

		$session = Session::create([
			'payment_method_types' => ['card'],
			'line_items' => [
				[
					'price' => 'price_123', // Replace with your actual product price ID
					'quantity' => 1,
				],
			],
			'mode' => 'payment',
			'success_url' => url('/success'),
			'cancel_url' => url('/cancel'),
		]);

		return response()->json(['id' => $session->id]);
	}
}
