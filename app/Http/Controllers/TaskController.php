<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Task;
use App\Models\User;

/**
 * @group Task
 *
 * APIs for tasks
 */
class TaskController extends Controller
{
  /**
   * Create a new task
   * 
   * This endpoint enable the admin to create new task
   * 
   * @authenticated
   * 
   * @bodyParam title string required
   * @bodyParam description string.
   * @bodyParam project_id int required The id of the project which the task belong to. 
   * @bodyParam user_id int required The id of the user assigned to this task.
   * 
   * @response 201 {
   *  "task": {
   *    "title": "task 2",
   *    "description": "description",
   *    "project_id": "1",
   *    "user_id": "11",
   *    "updated_at": "2022-09-30T21:20:07.000000Z",
   *    "created_at": "2022-09-30T21:20:07.000000Z",
   *    "id": 2
   *  }
   * }
   * 
   * @response 400 {
   *  "errors": {
   *    "title": [
   *        "The title field is required."
   *    ]
   *  }
   * }
   */
  public function create(Request $request)
  {
    $validator = Validator::make($request->all(), $this->getCreationValidation());

    if ($validator->fails())
      return response()->json(["errors" => $validator->errors()], 400);

    $validated = $validator->validated();
    $task = Task::create($validated);
    return response()->json(['task' => $task], 201);
  }

  private function getCreationValidation()
  {
    return [
      "title" => ["required", "string"],
      "description" => ["string"],
      "project_id" => ["required", "exists:projects,id"],
      "user_id" => ["exists:users,id"],
    ];
  }

  /**
   * Delete a task
   * 
   * This endpoint enable the admin to delete a task with specific id
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the task to be deleted.
   * 
   * @response 200 {
   *    "message": "The task has been deleted successfully"
   * }
   * 
   * @response 404 {
   *    "errors": "This task doesn't exist"
   * }
   */
  public function delete($id)
  {
    $task = Task::find($id);
    if (!$task) return response()->json(["errors" => "This task doesn't exist"], 404);

    $task->delete();
    return response()->json(['message' => "The task has been deleted successfully"], 200);
  }

  /**
   * Update a task
   * 
   * This endpoint enable the admin to update a task
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the task to be updated.
   * @bodyParam title string.
   * @bodyParam description string.
   * @bodyParam project_id int required The id of the project which the task belong to. 
   * @bodyParam user_id int required The id of the user assigned to this task.
   * @bodyParam detail string 
   * @bodyParam done boolean
   * 
   * @response 201 {
   *    "message": "The task has been updated successfully"
   * }
   * 
   * @response 404 {
   *    "errors": "This task doesn't exist"
   * }
   * 
   * @response 400 {
   *  "errors": {
   *    "done": [
   *        "The done field must be true or false."
   *    ],
   *    "project_id": [
   *        "The selected project id is invalid."
   *    ]
   *  }
   * }
   */
  public function update($id, Request $request)
  {
    $task = Task::find($id);
    if (!$task) return response()->json(["errors" => "This task doesn't exist"], 404);

    $validator = Validator::make($request->all(), $this->getUpdatingValidation());
    if ($validator->fails()) return response()->json(["errors" => $validator->errors()], 400);

    $validated = $validator->validated();
    $task->update($validated);

    return response()->json(['message' => "The task has been updated successfully"], 200);
  }

  private function getUpdatingValidation()
  {
    return [
      "title" => ["string"],
      "description" => ["string"],
      "detail" => ["string"],
      "done" => ["boolean"],
      "project_id" => ["exists:tasks,id"],
      "user_id" => ["exists:users,id"],
    ];
  }

  /**
   * Get a task
   * 
   * This endpoint enable the admin to get a task with specific id
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the task to be gotten.
   * 
   * @response 200 {
   *  "task": {
   *    "id": 3,
   *    "title": "task 33",
   *    "description": "description",
   *    "detail": null,
   *    "done": 0,
   *    "project_id": 1,
   *    "user_id": 11,
   *    "created_at": "2022-09-30T21:25:30.000000Z",
   *    "updated_at": "2022-09-30T21:30:18.000000Z"
   *  }
   * }
   * 
   * @response 404 {
   *    "errors": "This task doesn't exist"
   * }
   */
  public function get($id)
  {
    $task = Task::find($id);
    if (!$task) return response()->json(["errors" => "This task doesn't exist"], 404);

    return response()->json(["task" => $task], 200);
  }

  /**
   * List the tasks
   * 
   * This endpoint enable the admin to get all tasks
   * 
   * @authenticated
   * 
   * @response 200 {
   *  "tasks": [
   *    {
   *        "id": 2,
   *        "title": "task 2",
   *        "description": "description",
   *        "detail": null,
   *        "done": 0,
   *        "project_id": 1,
   *        "user_id": 11,
   *        "created_at": "2022-09-30T21:20:07.000000Z",
   *        "updated_at": "2022-09-30T21:20:07.000000Z"
   *    },
   *    {
   *        "id": 3,
   *        "title": "task 33",
   *        "description": "description",
   *        "detail": null,
   *        "done": 0,
   *        "project_id": 1,
   *        "user_id": 11,
   *        "created_at": "2022-09-30T21:25:30.000000Z",
   *        "updated_at": "2022-09-30T21:30:18.000000Z"
   *    }
   *  ]
   * }
   * 
   */
  public function list()
  {
    $tasks = Task::all();
    return response()->json(["tasks" => $tasks], 200);
  }


  /**
   * Submit a task
   * 
   * This endpoint enable the user to submit a task
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the task to be submitted.
   * @bodyParam detail string 
   * 
   * @response 201 {
   *    "message": "The task has been submitted successfully"
   * }
   * 
   * @response 400 {
   *    "errors": "The task is already submitted"
   * }
   * 
   * @response 403 {
   *    "errors": "You are not assigned to this task"
   * }
   */
  public function submit($id, Request $request)
  {
    $request['done'] = true;
    $task = Task::find($id);
    if (!$task || $task->user_id != auth()->user()->id) return response()->json(["errors" => "You are not assigned to this task"], 403);
    if($task->done) return response()->json(["errors" => "The task is already submitted"], 400);
    $validator = Validator::make($request->all(), [
      'detail' => ['string'],
      'done' => ['required', 'boolean']
    ]);

    if ($validator->fails()) return response()->json(["errors" => $validator->errors()], 400);

    $validated = $validator->validated();
    $task->update($validated);

    return response()->json(['message' => "The task has been submitted successfully"], 201);
  }

  /**
   * Assign user for a task
   * 
   * This endpoint enable the admin to assign a user for a task
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the task.
   * @urlParam user_id int required The id of the user to be assigned.
   * 
   * @response 201 {
   *    "message": "The task has been submitted to Sofyan successfully"
   * }
   * 
   * @response 404 {
   *    "errors": "This user doesn't exist"
   * }
   * 
   * @response 404 {
   *    "errors": "This task doesn't exist"
   * }
   */
  public function assignEmployee($id, $user_id)
  {
    $task = Task::find($id);
    if (!$task) return response()->json(["errors" => "This task doesn't exist"], 404);

    $user = User::find($user_id);
    if (!$user) return response()->json(["errors" => "This user doesn't exist"], 404);

    $task->update(['user_id' => $user_id]);
    return response()->json(['message' => "The task has been submitted to " . $user->name . " successfully"], 201);
  }

  /**
   * Remove assigned user from a task
   * 
   * This endpoint enable the admin to remove the assigned user from a task
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the task.
   * 
   * @response 201 {
   *    "message": "There is no user assigned to this task now"
   * }
   * 
   * @response 404 {
   *    "errors": "This task doesn't exist"
   * }
   */
  public function noEmployee($id)
  {
    $task = Task::find($id);
    if (!$task) return response()->json(["errors" => "This task doesn't exist"], 404);

    $task->update(['user_id' => null]);
    return response()->json(['message' => "There is no user assigned to this task now"], 201);
  }
}
