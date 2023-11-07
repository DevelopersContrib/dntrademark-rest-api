<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Thujohn\Twitter\Twitter;

class TwitterController extends Controller
{
    public function getTweets($userName) {
        try {
            $twitter = new Twitter();
            $tweets = $twitter->getUserTimeline(['screen_name' => $userName, 'count' => 20, 'response_format' => 'json']);

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
            throw $th;
        }
    }
}
