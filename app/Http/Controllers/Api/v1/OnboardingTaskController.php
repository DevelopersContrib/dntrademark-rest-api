<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OnboardingTask;
use App\Models\OnboardingTasksUser;
use Illuminate\Http\JsonResponse;

class OnboardingTaskController extends Controller
{
    public function index () : JsonResponse {
        try {
            $tasks = OnboardingTask::all();

            return response()->json([
                'success' => true,
                'tasks' => $tasks
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getUserTasks(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $tasks = OnboardingTask::all();

            foreach ($tasks as $task) {
                $usertask = OnboardingTasksUser::where('user_id', $user->id)
                            ->where('task_id', $task->id)
                            ->get(); 

                $task->is_done = count($usertask) > 0 ? 1 : 0;
            }


            return response()->json([
                'success' => true,
                'user_tasks' => $tasks,
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
