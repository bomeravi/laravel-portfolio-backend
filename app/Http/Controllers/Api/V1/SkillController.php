<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Skill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SkillController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Skill::all()
        ]);
    }

    public function list(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Skill::where('status',1)->get()
        ]);
    }
}
