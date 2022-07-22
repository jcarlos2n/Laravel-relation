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

            // $tasks =  Task::query()
            // ->where('user_id', $user_id)
            // ->get()
            // ->toArray();

            $tasks = User::find($user_id)->tasks;

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

    public function getTaskById($id)
    {
        try {

            $user_id = auth()->user()->id;
            $tasks =  Task::query()
                ->where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->get()
                ->toArray();

            if (!$tasks) {
                return [
                    'success' => true,
                    "message" => "These task doesn exist"
                ];
            }

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

    public function updateTask(Request $request, $id)
    {
        try {
            $user_id = auth()->user()->id;
            $task =  Task::query()
                ->where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();
                if (!$task) {
                    return [
                        'success' => true,
                        "message" => "These task doesn exist"
                    ];
                }

            Log::info("Updating tasks");
            $task = Task::find($id);
            if (!$task) {
                return response()->json([
                    "success" => true,
                    "message" => "Task doesnt exist"
                ], 404);

            }

            $title = $request->input('title');

            $task->title = $title;

            $task->save();
            return response()->json([
                "success" => true,
                "message" => "Task updated"
            ], 200);

        } catch (\Exception $exception) {

            Log::error("Error updating tasks: " . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating task' . $exception->getMessage(),
            ], 500);

        }
    }

    public function deleteTask($id){
        try {
            
            $user_id = auth()->user()->id;
            $task =  Task::query()
                ->where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();
                if (!$task) {
                    return [
                        'success' => true,
                        "message" => "These task doesn exist"
                    ];
                }

            Log::info("Deleting tasks");
            $task = Task::find($id);
            if (!$task) {
                return response()->json([
                    "success" => true,
                    "message" => "Task doesnt exist"
                ], 404);

            }
            $task::destroy($id);
            return response()->json([
                "success" => true,
                "message" => "Task deleted"
            ], 200);

        } catch (\Exception $exception) {

            Log::error("Error deleting tasks: " . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting task' . $exception->getMessage(),
            ], 500);
            
        }

    }
}
