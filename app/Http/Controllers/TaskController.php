<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function createtask(Request $request)
    { {
            try {
                Log::info("Creating a task");

                $validator = Validator::make($request->all(), [
                    'title' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response()->json(
                        [
                            "success" => false,
                            "message" => $validator->errors()
                        ],
                        400
                    );
                };

                $title = $request->input('title');
                $userId = auth()->user()->id;

                $task = new Task();
                $task->title = $title;
                $task->user_id = $userId;

                $task->save();


                return response()->json(
                    [
                        'success' => true,
                        'message' => "Task created"
                    ],
                    200
                );
            } catch (\Exception $exception) {
                Log::error("Error creating task: " . $exception->getMessage());

                return response()->json(
                    [
                        'success' => false,
                        'message' => "Error creating tasks"
                    ],
                    500
                );
            }
        }
    }
    public function getTask()
    {
        try {

            $user_id = auth()->user()->id;
            $tasks =  Task::query()->where('user_id', $user_id)->get()->toArray();
            Log::info("Getting all tasks");
            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieve successfully',
                'data' => $tasks
            ], 200);
        } catch (\Exception $exception) {

            Log::error("Error getting tasks: " . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting task' . $exception->getMessage(),

            ], 500);
        }
    }
}
