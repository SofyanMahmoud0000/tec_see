<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Project;

/**
 * @group Project
 *
 * APIs for projects
 */
class ProjectController extends Controller
{
  /**
   * Create a new project
   * 
   * This endpoint enable the admin to create new project
   * 
   * @authenticated
   * 
   * @bodyParam title string required
   * @bodyParam description string.
   * 
   * @response 201 {
   *  "project": {
   *    "title": "project 2",
   *    "description": "description",
   *    "updated_at": "2022-09-30T20:10:47.000000Z",
   *    "created_at": "2022-09-30T20:10:47.000000Z",
   *    "id": 1
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
    $project = Project::create($validated);
    return response()->json(['project' => $project], 201);
  }

  private function getCreationValidation()
  {
    return [
      "title" => ["required", "string"],
      "description" => ["string"]
    ];
  }


  /**
   * Delete a project
   * 
   * This endpoint enable the admin to delete a project with specific id
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the project to be deleted.
   * 
   * @response 200 {
   *    "message": "The project has been deleted successfully"
   * }
   * 
   * @response 404 {
   *    "errors": "This project doesn't exist"
   * }
   */
  public function delete($id)
  {
    $project = Project::find($id);
    if (!$project) return response()->json(["errors" => "This project doesn't exist"], 404);

    $project->delete();
    return response()->json(['message' => "The project has been deleted successfully"], 200);
  }

  /**
   * Update a project
   * 
   * This endpoint enable the admin to update a project
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the project to be updated.
   * @bodyParam title string.
   * @bodyParam description string.
   * 
   * @response 201 {
   *    "message": "The project has been updated successfully"
   * }
   * 
   * @response 404 {
   *    "errors": "This project doesn't exist"
   * }
   */
  public function update($id, Request $request)
  {
    $project = Project::find($id);
    if (!$project) return response()->json(["errors" => "This project doesn't exist"], 404);

    $validator = Validator::make($request->all(), $this->getUpdatingValidation());
    if ($validator->fails()) return response()->json(["errors" => $validator->errors()], 400);

    $validated = $validator->validated();
    $project->update($validated);

    return response()->json(['message' => "The project has been updated successfully"], 201);
  }

  private function getUpdatingValidation()
  {
    return [
      "title" => ["string"],
      "description" => ["string"]
    ];
  }

  /**
   * Get a project
   * 
   * This endpoint enable the admin to get a project with specific id
   * 
   * @authenticated
   * 
   * @urlParam id int required The id of the project to be gotten.
   * 
   * @response 200 {
   *  "project": {
   *    "id": 1,
   *    "title": "updated title",
   *    "description": null,
   *    "created_at": "2022-09-30T20:10:47.000000Z",
   *    "updated_at": "2022-09-30T20:49:13.000000Z"
   *  }
   * }
   * 
   * @response 404 {
   *    "errors": "This project doesn't exist"
   * }
   */
  public function get($id)
  {
    $project = Project::find($id);
    if (!$project) return response()->json(["errors" => "This project doesn't exist"], 404);

    return response()->json(["project" => $project], 200);
  }

  /**
   * List the projects
   * 
   * This endpoint enable the admin to get all projects
   * 
   * @authenticated
   * 
   * @response 200 {
   *  "projects": [
   *    {
   *        "id": 1,
   *        "title": "updated title",
   *        "description": null,
   *        "created_at": "2022-09-30T20:10:47.000000Z",
   *        "updated_at": "2022-09-30T20:49:13.000000Z"
   *    },
   *    {
   *        "id": 4,
   *        "title": "project 3",
   *        "description": "description",
   *        "created_at": "2022-09-30T20:54:40.000000Z",
   *        "updated_at": "2022-09-30T20:54:40.000000Z"
   *    }
   *  ]
   * }
   * 
   */
  public function list()
  {
    $projects = Project::all();
    return response()->json(["projects" => $projects], 200);
  }
}
