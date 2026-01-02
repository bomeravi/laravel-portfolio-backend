<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;

class PortfolioItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $portfolioItems = PortfolioItem::where('title', 'LIKE', "%$keyword%")
                ->orWhere('category', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $portfolioItems = PortfolioItem::latest()->paginate($perPage);
        }

        return view('admin.portfolio-items.index', compact('portfolioItems'));
    }

    public function create()
    {
        return view('admin.portfolio-items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image_file' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $data = $request->except(['image_file', 'image_url']);
        
         if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('images/portfolio', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $data['status'] = $request->has('status');
        $data['featured_homepage'] = $request->has('featured_homepage');

        if ($request->has('tags') && is_string($request->tags)) {
             $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        PortfolioItem::create($data);

        return redirect()->route('admin.portfolio-items.index')->with('flash_message', 'Portfolio Item added!');
    }

    public function edit($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        return view('admin.portfolio-items.edit', compact('portfolioItem'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
             'category' => 'required|string|max:255',
            'image_file' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $portfolioItem = PortfolioItem::findOrFail($id);
        $data = $request->except(['image_file', 'image_url']);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('images/portfolio', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $data['status'] = $request->has('status');
        $data['featured_homepage'] = $request->has('featured_homepage');

         if ($request->has('tags') && is_string($request->tags)) {
             $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $portfolioItem->update($data);

        return redirect()->route('admin.portfolio-items.index')->with('flash_message', 'Portfolio Item updated!');
    }

    public function destroy($id)
    {
        PortfolioItem::destroy($id);

        return redirect()->route('admin.portfolio-items.index')->with('flash_message', 'Portfolio Item deleted!');
    }
}
