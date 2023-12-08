<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class InvoiceController extends Controller
{
    public function index (Request $request) {
        try {
            // $user = $request->user();

            // $invoices = Invoice::where('user_id', $user->id)->paginate();

            // return response()->json([
            //     'success' => true,
            //     'invoices' => $invoices
            // ]);

            $user = $request->user();
			$noItemsPerPage = $request->limit ? $request->limit : 10;

			$orderBy = !empty($request->sortBy) ? $request->orderBy: 'desc';
			$sortBy = !empty($request->sortBy) ? $request->sortBy: 'id';
			$searchBy = !empty($request->searchBy) ? $request->searchBy : 'desc';
			$searchKey = $request->search;

			if ($searchKey) {
				$invoices = Invoice::where('user_id', $user->id)
					->where($searchBy, 'like', '%' . $searchKey . '%')
					->orderBy($sortBy, $orderBy) 
					->paginate($noItemsPerPage);
			} else {
				$invoices = Invoice::where('user_id', $user->id)
					->orderBy($sortBy, $orderBy)
					->paginate($noItemsPerPage);
			}

			return response()->json([
                'success' => true,
                'invoices' => $invoices
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function show($id) : JsonResponse {
        try {
            $invoice = Invoice::find($id);
            
            return response()->json([
                'success' => true,
                'invoice' => $invoice
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

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

    public function count(Request $request) {
        try {
            $user = $request->user();

            $count = Invoice::where('status', 'pending')->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
}
