<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ExperienceController extends Controller
{
    public function index(): JsonResponse
    {
        $experiences = Experience::orderBy('start_date', 'desc')->get();

        return response()->json($experiences);
    }

    public function list(): JsonResponse
    {
        $experiences = Experience::orderBy('start_date', 'desc')->where('status',1)->get();

        return response()->json($experiences);
    }

    public function show(Experience $experience): JsonResponse
    {
        return response()->json($experience);
    }
}
