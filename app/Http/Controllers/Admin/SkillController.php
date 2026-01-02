<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $skills = Skill::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $skills = Skill::latest()->paginate($perPage);
        }

        return view('admin.skills.index', compact('skills'));
    }

    public function create()
    {
        return view('admin.skills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:0|max:100',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status'); // Helper to ensure boolean if checkbox

        if ($request->has('tags') && is_string($request->tags)) {
             $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        Skill::create($data);

        return redirect()->route('admin.skills.index')->with('flash_message', 'Skill added!');
    }

    public function edit($id)
    {
        $skill = Skill::findOrFail($id);
        return view('admin.skills.edit', compact('skill'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:0|max:100',
        ]);

        $skill = Skill::findOrFail($id);
        
        $data = $request->all();
        $data['status'] = $request->has('status');

        if ($request->has('tags') && is_string($request->tags)) {
             $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $skill->update($data);

        return redirect()->route('admin.skills.index')->with('flash_message', 'Skill updated!');
    }

    public function destroy($id)
    {
        Skill::destroy($id);
        return redirect()->route('admin.skills.index')->with('flash_message', 'Skill deleted!');
    }
}
