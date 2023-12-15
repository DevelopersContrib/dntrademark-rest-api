<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public static function saveLead ($email, $domain) {
        try {
            // Create a new Guzzle client
            $client = new Client();

            // Specify the URL for the POST request
            $url = 'https://www.contrib.com/forms/saveleads';

            // Data to be sent as form parameters
            $formData = [
                'email' => $email,
                'domain' => $domain
            ];

            // Make a POST request with form parameters
            $response = $client->post($url, [
                'form_params' => $formData,
            ]);

            // Get the response body as a string
            $body = $response->getBody()->getContents();

            // You can now work with the response data (e.g., decode JSON if the response is JSON)
            $data = json_decode($body, true);
            // Do something with the data...

            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
