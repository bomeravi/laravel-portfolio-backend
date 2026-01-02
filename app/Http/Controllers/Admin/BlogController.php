<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $blogs = Blog::where('title', 'LIKE', "%$keyword%")
                ->orWhere('content', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $blogs = Blog::latest()->paginate($perPage);
        }

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.blogs.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blogs,slug',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image_file' => 'nullable|image|max:2048',
            'featured_image_url' => 'nullable|url',
        ]);

        $data = $request->except(['featured_image_file', 'featured_image_url', 'tags']);

        // Handle Image Upload
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('images/blogs', 'public');
            $data['featured_image'] = '/storage/' . $path;
        } elseif ($request->filled('featured_image_url')) {
            $data['featured_image'] = $request->featured_image_url;
        }

        $data['user_id'] = auth()->id() ?? 1; 
        if (!$request->has('user_id') && auth()->check()) {
             $data['user_id'] = auth()->id();
        }
        
        $data['is_published'] = $request->has('is_published');
        $data['status'] = $request->has('status');
        $data['featured_homepage'] = $request->has('featured_homepage');
        
        if (empty($data['reading_time'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['reading_time'] = ceil($wordCount / 200); 
        }

        $blog = Blog::create($data);

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return redirect()->route('admin.blogs.index')->with('flash_message', 'Blog added!');
    }

    public function show($id)
    {
        $blog = Blog::with(['user', 'category', 'tags'])->findOrFail($id);
        return view('admin.blogs.show', compact('blog'));
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.blogs.edit', compact('blog', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blogs,slug,' . $id,
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image_file' => 'nullable|image|max:2048',
            'featured_image_url' => 'nullable|url',
        ]);

        $blog = Blog::findOrFail($id);
        $data = $request->except(['featured_image_file', 'featured_image_url', 'tags']);

        if ($request->hasFile('featured_image_file')) {
            if ($blog->featured_image && file_exists(public_path(str_replace('/storage/', 'storage/', $blog->featured_image)))) {
                 // optionally delete
            }
            $path = $request->file('featured_image_file')->store('images/blogs', 'public');
            $data['featured_image'] = '/storage/' . $path;
        } elseif ($request->filled('featured_image_url')) {
            $data['featured_image'] = $request->featured_image_url;
        }

        $data['is_published'] = $request->has('is_published');
        $data['status'] = $request->has('status');
        $data['featured_homepage'] = $request->has('featured_homepage');

        if (empty($data['reading_time'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['reading_time'] = ceil($wordCount / 200);
        }

        $blog->update($data);

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        } else {
            $blog->tags()->detach();
        }

        return redirect()->route('admin.blogs.index')->with('flash_message', 'Blog updated!');
    }

    public function destroy($id)
    {
        Blog::destroy($id);
        return redirect()->route('admin.blogs.index')->with('flash_message', 'Blog deleted!');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('images/blogs', 'public');
            $url = asset('storage/' . $path); 
            return response()->json(['url' => $url]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
