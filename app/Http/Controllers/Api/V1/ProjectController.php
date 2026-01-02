<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::orderBy('created_at', 'desc')->get();

        return response()->json($projects);
    }

    public function list(): JsonResponse
    {
        $projects = Project::orderBy('created_at', 'desc')->where('status',1)->get();

        return response()->json($projects);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($project);
    }
}
