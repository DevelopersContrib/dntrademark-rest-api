<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDomainItemRequest;
use App\Models\Domain;
use App\Models\DomainItem;
use App\Models\DomainsItemsOwner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DomainItemController extends Controller
{
    public function index (Request $request, $id)
    {
        try {
			$noItemsPerPage = $request->limit ? $request->limit : 10;
			$orderBy = !empty($request->sortBy) ? $request->orderBy: 'desc';
			$sortBy = !empty($request->sortBy) ? $request->sortBy: 'domain_id';
			$filterBy = !empty($request->filterBy) ? $request->filterBy : 'keyword';
			$searchKey = $request->filter;

			if ($searchKey) {
				$items = DomainItem::with('domain')
                    ->where('domain_id', '=', $id)
					->where($filterBy, 'like', '%' . $searchKey . '%')
					->orderBy($sortBy, $orderBy) 
					->paginate($noItemsPerPage);
			} else {
				$items = DomainItem::with('domain')
                    ->where('domain_id', '=', $id)
					->orderBy($sortBy, $orderBy)
					->paginate($noItemsPerPage);
			}

			return response()->json([
				'succes' => true,
				'items' => $items
			], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
				'succes' => false,
				'message' => $e->getMessage()
			], JsonResponse::HTTP_OK);
        }
    }

    public function store (StoreDomainItemRequest $request, Domain $domain): JsonResponse
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

    public function getItemOwner (Request $request, $itemId) {
        try {
            $item = DomainItem::with('domain')->where('id', '=', $itemId)->first();
            $owner = DomainsItemsOwner::where('item_id', '=', $itemId)->first();
            $item['owner'] = $owner;

            return response()->json([
                'success' => true,
                'item' => $item
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
