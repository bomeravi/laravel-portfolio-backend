<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $projects = Project::where('title', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $projects = Project::latest()->paginate($perPage);
        }

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'live_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'list_order' => 'nullable|integer',
        ]);

        $data = $request->except(['image_file', 'image_url']);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('images/projects', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        // Convert comma-separated tags to array
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Handle checkbox fields
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['featured_homepage'] = $request->has('featured_homepage') ? 1 : 0;

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('flash_message', 'Project added!');
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'live_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'list_order' => 'nullable|integer',
        ]);

        $project = Project::findOrFail($id);
        $data = $request->except(['image_file', 'image_url']);

        if ($request->hasFile('image_file')) {
            // Optional: Delete old image if it exists and is local
            if ($project->image && file_exists(public_path(str_replace('/storage/', 'storage/', $project->image)))) {
                // @unlink(public_path(str_replace('/storage/', 'storage/', $project->image)));
            }
            
            $path = $request->file('image_file')->store('images/projects', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        // Convert comma-separated tags to array
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Handle checkbox fields
        // $data['status'] = $request->has('status') ? 1 : 0;
        // $data['featured_homepage'] = $request->has('featured_homepage') ? 1 : 0;

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('flash_message', 'Project updated!');
    }

    public function destroy($id)
    {
        Project::destroy($id);
        return redirect()->route('admin.projects.index')->with('flash_message', 'Project deleted!');
    }
}
