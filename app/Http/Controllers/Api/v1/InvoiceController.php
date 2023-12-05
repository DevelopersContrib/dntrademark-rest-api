<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public static  function create($userId) {
        try {
            // Create a new Guzzle client
            $client = new Client();

            // Specify the URL for the POST request
            $url = 'https://manage.vnoc.com/v2/dntrademarks/createinvoice';

            // Data to be sent as form parameters
            $formData = [
                'userid' => $userId,
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
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
