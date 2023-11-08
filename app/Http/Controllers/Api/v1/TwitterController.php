<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Atymic\Twitter\Contract\Twitter;

class TwitterController extends Controller
{
    protected $twitter;

    public function __construct(Twitter $twitter)
    {
        $this->twitter = $twitter;
    }

    public function getTweets($userName) {
        try {
            $tweets = $this->twitter->getTweets([$userName], []);

            echo '<pre>';
            var_dump($tweets);
            echo '</pre>';
            exit;

            if (!empty($tweets)) {
                return response()->json([
                    'success' => true,
                    'tweets' => $tweets
                ], JsonResponse::HTTP_OK);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'No tweets found.'
                ], JsonResponse::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            // Handle the exception appropriately
            throw $th;
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching tweets.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
