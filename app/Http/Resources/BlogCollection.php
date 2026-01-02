<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class BlogCollection extends BaseCollection
{
    protected function mapData(Request $request): array
    {
        return $this->collection->map(function ($blog) {

            $item = [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'excerpt' => $blog->excerpt,
                'content' => $blog->content,
                'featured_image' => $blog->featured_image,
                'reading_time' => $blog->reading_time,
                'published_at' => $blog->published_at,
                'is_published' => $blog->is_published,
                'featured_homepage' => $blog->featured_homepage,
                'status' => $blog->status,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ];

            if ($blog->relationLoaded('category') && $blog->category) {
                $item['category'] = [
                    'id' => $blog->category->id,
                    'slug' => $blog->category->slug,
                    'name' => $blog->category->name,
                ];
            }

            if ($blog->relationLoaded('user') && $blog->user) {
                $item['user'] = [
                    'id' => $blog->user->id,
                    'name' => $blog->user->name,
                    'avatar' => $blog->user->avatar,
                ];
            }

            if ($blog->relationLoaded('tags') && $blog->tags->isNotEmpty()) {
                $item['tags'] = $blog->tags->map(fn ($tag) => [
                    'slug' => $tag->slug,
                    'name' => $tag->name,
                ])->values();
            }

            return $item;
        })->values()->all();
    }
}
