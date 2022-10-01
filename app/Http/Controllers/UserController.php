<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class UserController extends Controller
{
  /**
   * Get all tasks
   * 
   * @group Tasks for users
   * 
   * This endpoint returns all the user's tasks
   * 
   * @authenticated
   * 
   * @response 200 {
   *  "tasks": [
   *        {
   *            "id": 1,
   *            "title": "Task 1",
   *            "description": "description",
   *            "detail": null,
   *            "done": 0,
   *            "project_id": 1,
   *            "user_id": 11,
   *            "created_at": "2022-09-29T15:04:38.000000Z",
   *            "updated_at": "2022-09-29T16:56:22.000000Z"
   *        },
   *        {
   *            "id": 2,
   *            "title": "Task 2",
   *            "description": "description",
   *            "detail": "The tasks has been finished by sofyan",
   *            "done": 1,
   *            "project_id": 1,
   *            "user_id": 11,
   *            "created_at": "2022-09-29T15:10:12.000000Z",
   *            "updated_at": "2022-09-29T16:43:31.000000Z"
   *        }
   *    ]
   * }
   */
  public function getAllTasks()
  {
    $tasks = auth()->user()->tasks;
    return response()->json(['tasks' => $tasks], 200);
  }

  /**
   * Get all submitted tasks
   * 
   * @group Tasks for users
   * 
   * This endpoint returns all the user's submitted tasks
   * 
   * @authenticated
   * 
   * @response 200 {
   *  "tasks": [
   *     {
   *         "id": 2,
   *         "title": "Task 2",
   *         "description": "description",
   *         "detail": "The tasks has been finished by sofyan",
   *         "done": 1,
   *         "project_id": 1,
   *         "user_id": 11,
   *         "created_at": "2022-09-29T15:10:12.000000Z",
   *         "updated_at": "2022-09-29T16:43:31.000000Z"
   *     }
   *   ]
   * }
   */
  public function getSubmittedTasks()
  {
    $tasks = auth()->user()->tasks->where('done', true)->all();
    $tasks = array_values($tasks);
    return response()->json(['tasks' => $tasks], 200);
  }

  /**
   * Get all pending tasks
   * 
   * @group Tasks for users
   * 
   * This endpoint returns all the user's pending tasks
   * 
   * @authenticated
   * 
   * @response 200 {
   *  "tasks": [
   *     {
   *       "id": 1,
   *       "title": "Task 1",
   *       "description": "description",
   *       "detail": null,
   *       "done": 0,
   *       "project_id": 1,
   *       "user_id": 11,
   *       "created_at": "2022-09-29T15:04:38.000000Z",
   *       "updated_at": "2022-09-29T16:56:22.000000Z"
   *     }
   *  ]
   * }
   */
  public function getPendingTasks()
  {
    $tasks = auth()->user()->tasks->where('done', false);
    return response()->json(['tasks' => $tasks], 200);
  }

  /**
   * Get a specific task
   * 
   * @group Tasks for users
   * 
   * This endpoint return the task with specific Id.
   * The user must be assigned to this task to be able to get access the task
   * 
   * @authenticated
   * 
   * @urlParam id required number The id of the task the user want to get.
   * 
   * @response 200 {
   *  "task": {
   *    "id": 1,
   *    "title": "Task 1",
   *    "description": "description",
   *    "detail": null,
   *    "done": 0,
   *    "project_id": 1,
   *    "user_id": 11,
   *    "created_at": "2022-09-29T15:04:38.000000Z",
   *    "updated_at": "2022-09-29T16:56:22.000000Z"
   *  }
   * }
   * 
   * @response 403 {
   *    "errors": "This task doesn't belong to you"
   * }
   */
  public function getTask($id)
  {
    $task = Task::find($id);
    if (!$task || $task->user_id != auth()->user()->id) return response()->json(["errors" => "This task doesn't belong to you"], 403);
    return response()->json(['task' => $task], 200);
  }

  /**
   * Get all projects
   * 
   * @group Projects for users
   * 
   * This endpoint returns all the user's projects
   * 
   * @authenticated
   * 
   * @response 200 {
   *  "projects": [
   *    {
   *        "id": 1,
   *        "title": "Test project",
   *        "description": "new description",
   *        "created_at": "2022-09-29 15:04:27",
   *        "updated_at": "2022-09-29 15:04:27"
   *    }
   *  ]
   * }
   * 
   * 
   */
  public function getProjects()
  {
    $projects = auth()->user()->projects();
    return response()->json(['projects' => $projects], 200);
  }

  /**
   * Get a specific project
   * 
   * @group Projects for users
   * 
   * This endpoint return the project with specific Id.
   * The user must be assigned to a task belongs to this project to be able to get access the project
   * 
   * @authenticated
   * 
   * @urlParam id required number The id of the project the user want to get.
   * 
   * @response 200 {
   *  "project": {
   *    "id": 1,
   *    "title": "Test project",
   *    "description": "new description",
   *    "created_at": "2022-09-29 15:04:27",
   *    "updated_at": "2022-09-29 15:04:27"
   *  }
   * }
   * 
   * @response 403 {
   *    "errors": "This project doesn't belong to you"
   * }
   */
  public function getProject($id)
  {
    $project = auth()->user()->projects()->where("id", $id)->first();
    if (!$project) return response()->json(["errors" => "This project doesn't belong to you"], 403);
    return response()->json(['project' => $project], 200);
  }
}
