<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\Task;

/**
 * @OA\Info(
 *     title="My First Task API",
 *     version="0.1"
 * )
 */
class TaskController extends Controller
{
    /**
     * Get Task List
     * @OA\Get (
     *     path="/api/task",
     *     tags={"Task"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="Test Task"),
     *              @OA\Property(property="description", type="text", example="This is new test task"),
     *              @OA\Property(property="status", type="enum", example="pending"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Records not found"
     *     )
     * )
     */
    public function index()
    {
        // Get a list of tasks
        return Task::all();
    }

    /**
     * Create Task
     * @OA\Post (
     *     path="/api/createTask",
     *     tags={"Task"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create task here",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     description="Description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     description="Status",
     *                     type="string"
     *                 ),
     *                 required={"title", "description", "status"}
     *             )
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="title"),
     *              @OA\Property(property="description", type="text", example="description"),
     *              @OA\Property(property="status", type="enum", example="status"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *      )
     * )
     */
    public function store(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        // invalid request for task creation
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        // task creation method   
        $input = $request->all();
        $task = Task::create($input);

        // success response object  
        $response = [
            'success' => true,
            'data' => $task,
            'message' => 'Task created successfully'
        ];
        return response()->json($response, 200);
    }

    /**
     * Get Task
     * @OA\Get (
     *     path="/api/getTask/{id}",
     *     tags={"Task"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="title"),
     *              @OA\Property(property="description", type="text", example="description"),
     *              @OA\Property(property="status", type="enum", example="status"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Record not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        // fetching task by id method
        $task=Task::find($id);
        return $task;
    }

    /**
     * Update Task
     * @OA\Post (
     *     path="/api/updateTask/{id}",
     *     tags={"Task"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create task here",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     description="Description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     description="Status",
     *                     type="string"
     *                 ),
     *                 required={"title", "description", "status"}
     *             )
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="title"),
     *              @OA\Property(property="description", type="text", example="description"),
     *              @OA\Property(property="status", type="enum", example="status"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *      )
     * )
     */
    public function update(Request $request, string $id)
    {
        // validation
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        // invalid response for task updation
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }
        
        // task updation method 
        $task=Task::find($id);
        $task->update($request->all());
        
        // success response object
        $response = [
            'success' => true,
            'data' => $task,
            'message' => 'Task updated successfully'
        ];
        return response()->json($response, 200);
    }

    /**
     * Delete Task
     * @OA\Put (
     *     path="/api/deleteTask/{id}",
     *     tags={"Task"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="delete task success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Record not found"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $data = Task::destroy($id);
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Task deleted successfully'
        ];
        return response()->json($response, 200);
    }
}
