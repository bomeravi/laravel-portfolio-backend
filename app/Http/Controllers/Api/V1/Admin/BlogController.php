<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use App\Http\Resources\BlogCollection;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs with pagination + filters.
     */
    public function index(Request $request)
    {
        $query = Blog::query();

        // --- Search by title or slug ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        // --- Filter by exact date ---
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // --- Filter between two dates ---
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date,
                $request->to_date
            ]);
        }

        // Pagination (default: 10)
        $perPage = $request->get('per_page', 10);
        $blogs = $query->with(['category', 'user'])->orderBy('created_at', 'desc')->paginate($perPage);

        return new BlogCollection($blogs);
    }

    /**
     * Store a newly created blog.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt'       => 'nullable|string',
            'content'       => 'nullable|string',

            // fallback string
            'feature_image' => 'nullable|string',

            // actual uploaded image
            'image'         => 'nullable|image|max:2048',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // If image uploaded → store it
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $validated['feature_image'] = $path;
        }

        // If neither uploaded image nor feature_image provided → default
        if (!isset($validated['feature_image'])) {
            $validated['feature_image'] = 'defaults/blog.png';
        }

        $blog = Blog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully',
            'data' => $blog
        ], 201);
    }

    /**
     * Display the specified blog.
     */
    public function show(string $id)
    {
        $blog = Blog::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    /**
     * Update the specified blog.
     */
    public function update(Request $request, string $id)
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title'         => 'sometimes|string|max:255',
            'slug'          => "sometimes|string|max:255|unique:blogs,slug,{$id}",
            'excerpt'       => 'nullable|string',
            'content'       => 'nullable|string',

            // fallback string
            'feature_image' => 'nullable|string',

            // uploaded image
            'image'         => 'nullable|image|max:2048',
        ]);

        // Auto slug generation if title updated and slug missing
        if (!$request->filled('slug') && $request->filled('title')) {
            $validated['slug'] = Str::slug($request->title);
        }

        // If new image uploaded → store it
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $validated['feature_image'] = $path;
        }

        // If no new image and feature_image not provided → keep old image
        if (!isset($validated['feature_image'])) {
            $validated['feature_image'] = $blog->feature_image;
        }

        $blog->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
            'data' => $blog
        ]);
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);

        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully'
        ]);
    }
}
