<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getAllNotificationsByUser (Request $request) {
        try {
            $user = $request->user();

            $notifications = Notification::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => NotificationResource::collection($notifications)
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show ($id) {
        try {
            $notification = Notification::find($id);

            return response()->json([
                'success' => true,
                'message' => new NotificationResource($notification)
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
