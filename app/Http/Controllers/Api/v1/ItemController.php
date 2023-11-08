<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DomainsItemsOwner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getItem (Request $request, $itemId) : JsonResponse
    {
        try {
            $domainOwner = DomainsItemsOwner::with('item')
                    ->where('item_id', '=', $itemId)
                    ->first();
            
            return response()->json([
                'success' => true,
                'domainOwner' => $domainOwner
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
