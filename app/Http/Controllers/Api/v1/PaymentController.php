<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class PaymentController extends Controller
{
	protected $stripeKey;
	public function __construct()
	{
		$this->stripeKey = env('STRIPE_SECRET_TEST');
		Stripe::setApiKey($this->stripeKey);
	}

	public function createCharge(Request $request)
	{
		try {
			$intent = PaymentIntent::create([
				'amount' => $request->amount, // Amount in cents
				'currency' => 'usd',
				'payment_method' => 'pm_card_visa', // Payment method ID (you can obtain this from the frontend)
				'confirmation_method' => 'manual',
				'confirm' => true,
				'return_url' => $request->return_url
			]);

			if ($intent->status) {
				$user = $request->user();
				$currentDateTime = Carbon::now();

				$payment = new Payment();
				$payment->member_id = $user->id;
				$payment->payment_type = implode(',', $intent->payment_method_types);
				$payment->stripe_payment_status = $intent->status;
				$payment->result_json = json_encode($intent);
				$payment->mode_key = $this->stripeKey;
				$payment->date_paid =  $currentDateTime->format('Y-m-d');

				if ($payment->save()) {
					if (!empty($request->package_id)) {
						$user = User::find($user->id);
						$user->package_id = $request->package_id;
						$user->save();
					}
				}

				return response()->json([
					'success' => true,
					'intent' => $intent
				], JsonResponse::HTTP_OK);
			} else {
				return response()->json([
					'success' => false,
					'status' => $intent->status
				], JsonResponse::HTTP_OK);
			}
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], JsonResponse::HTTP_ACCEPTED);
		}
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
