<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\BlogCollection;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
     public function index(Request $request): JsonResponse
    {
        $blogs = Blog::with([ 'category', 'tags'])
                    ->published()
                    ->orderBy('published_at', 'desc')
					->paginate('10');
        return response()->json(BlogCollection::make($blogs));
        // return response()->json($blogs);
    }

    public function list(Request $request): JsonResponse
    {
        $blogs = Blog::with(['category', 'tags'])
                    ->published()
                    ->homepageFeatured()
                    ->orderBy('published_at', 'desc')
                    ->limit(6)
                    ->get();

        return response()->json(BlogCollection::make($blogs));
        // return response()->json($blogs);
    }

    public function show(string $slug): JsonResponse
    {
        $blog = Blog::with(['user', 'category', 'tags'])
                    ->published()
                    ->where('slug', $slug)
                    ->firstOrFail();

        return response()->json($blog);
    }

    public function byCategory(string $categorySlug): JsonResponse
    {
        $blogs = Blog::with(['user', 'category', 'tags'])
                    ->whereHas('category', function ($query) use ($categorySlug) {
                        $query->where('slug', $categorySlug);
                    })
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->get();

        return response()->json($blogs);
    }

    public function byTag(string $tagSlug): JsonResponse
    {
        $blogs = Blog::with(['user', 'category', 'tags'])
                    ->whereHas('tags', function ($query) use ($tagSlug) {
                        $query->where('slug', $tagSlug);
                    })
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->get();

        return response()->json($blogs);
    }
}
