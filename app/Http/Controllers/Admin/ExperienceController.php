<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        // Eager loading not strictly needed as no relationships shown, but good practice if needed
        if (!empty($keyword)) {
            $experiences = Experience::where('title', 'LIKE', "%$keyword%")
                ->orWhere('company', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $experiences = Experience::latest()->paginate($perPage);
        }

        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['current'] = $request->has('current');
        $data['display_month'] = $request->has('display_month');
        $data['status'] = $request->has('status');

        if ($request->has('achievements') && is_string($request->achievements)) {
             // Split by newline and trim
             $data['achievements'] = array_filter(array_map('trim', explode("\n", $request->achievements)));
        }

        Experience::create($data);

        return redirect()->route('admin.experiences.index')->with('flash_message', 'Experience added!');
    }

    public function edit($id)
    {
        $experience = Experience::findOrFail($id);
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $experience = Experience::findOrFail($id);
        
        $data = $request->all();
        $data['current'] = $request->has('current');
        $data['display_month'] = $request->has('display_month');
        $data['status'] = $request->has('status');

        if ($request->has('achievements') && is_string($request->achievements)) {
             $data['achievements'] = array_filter(array_map('trim', explode("\n", $request->achievements)));
        }

        $experience->update($data);

        return redirect()->route('admin.experiences.index')->with('flash_message', 'Experience updated!');
    }

    public function destroy($id)
    {
        Experience::destroy($id);
        return redirect()->route('admin.experiences.index')->with('flash_message', 'Experience deleted!');
    }
}
