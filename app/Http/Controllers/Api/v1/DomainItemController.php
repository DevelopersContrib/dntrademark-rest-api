<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDomainItemRequest;
use App\Models\Domain;
use App\Models\DomainItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DomainItemController extends Controller
{
    //
    public function store(StoreDomainItemRequest $request, Domain $domain): JsonResponse
    {
        try {
            $domainItem = new DomainItem();
            $domainItem->domain_id = $domain->id;
            $domainItem->keyword = $request->keyword;
            $domainItem->registration_number = $request->registration_number;
            $domainItem->serial_number = $request->serial_number;
            $domainItem->status_label = $request->status_label;
            $domainItem->status_definition = $request->status_definition;

            if ($domainItem->save()) {
                return response()->json([
                    'success' => true,
                    'item' => $domainItem
                ], JsonResponse::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_ACCEPTED);
        }
    }
}
