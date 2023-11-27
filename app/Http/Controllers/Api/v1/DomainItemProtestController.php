<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\DomainItemProtest;

class DomainItemProtestController extends Controller
{
    public function index () {
        try {
            $itemProtests = DomainItemProtest::all();

            return response()->json([
                'success' => true,
                'item_protests' => $itemProtests
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'item_id' => ['required', 'integer'],
                'title' => ['required', 'string'],
                'content' => ['required', 'string'],
            ]);

            $user = $request->user();
            $status = false;

            $itemProtest = new DomainItemProtest();
            $itemProtest->member_id = $user->id;
            $itemProtest->item_id = $validatedData['item_id'];
            $itemProtest->title = $validatedData['title'];
            $itemProtest->content = $validatedData['content'];

            if ($itemProtest->save()) {
                $status = true;
            }

            $user = $request->user();

            return response()->json([
                'success' => $status,
                'item_protest' => $itemProtest
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateItemProtest (Request $request, $id) {
        try {
            $validatedData = $request->validate([
                'item_id' => ['required', 'integer'],
                'title' => ['nullable', 'string'],
                'content' => ['nullable', 'string'],
            ]);
    
            $validatedData = array_filter($validatedData);

            $itemProstest = DomainItemProtest::find($id);
            echo '<pre>';
            var_dump($validatedData);
            var_dump($itemProstest->update($validatedData));
            echo '</pre>';
            exit;
    
            return response()->json([
                'success' => true,
                'item_protest' => $itemProstest
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
