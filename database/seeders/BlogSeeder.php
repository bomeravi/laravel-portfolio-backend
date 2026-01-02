<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'UI/UX Design', 'slug' => 'ui-ux-design'],
            ['name' => 'Mobile Development', 'slug' => 'mobile-development'],
            ['name' => 'DevOps', 'slug' => 'devops'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create tags
        $tags = [
            ['name' => 'React', 'slug' => 'react'],
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'TypeScript', 'slug' => 'typescript'],
            ['name' => 'Tailwind CSS', 'slug' => 'tailwind-css'],
            ['name' => 'API Development', 'slug' => 'api-development'],
            ['name' => 'Database Design', 'slug' => 'database-design'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        // Get a user
        $user = User::first();

        // Create blogs
        $blogs = [
            [
                'title' => 'Getting Started with React and TypeScript',
                'slug' => 'getting-started-with-react-and-typescript',
                'excerpt' => 'Learn how to set up a React project with TypeScript and build your first component with type safety.',
                'content' => 'Full blog content here...',
                'featured_image' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&q=80',
                'reading_time' => 8,
                'published_at' => Carbon::now()->subDays(2),
                'is_published' => true,
                'user_id' => $user->id,
                'category_id' => Category::where('slug', 'web-development')->first()->id,
                'tags' => ['react', 'typescript', 'javascript'],
            ],
            [
                'title' => 'Building RESTful APIs with Laravel',
                'slug' => 'building-restful-apis-with-laravel',
                'excerpt' => 'A comprehensive guide to building robust RESTful APIs using Laravel framework with best practices.',
                'content' => 'Full blog content here...',
                'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&q=80',
                'reading_time' => 12,
                'published_at' => Carbon::now()->subDays(5),
                'is_published' => true,
                'user_id' => $user->id,
                'category_id' => Category::where('slug', 'web-development')->first()->id,
                'tags' => ['laravel', 'api-development', 'php'],
            ],
            [
                'title' => 'Modern UI Design Principles for 2024',
                'slug' => 'modern-ui-design-principles-2024',
                'excerpt' => 'Explore the latest UI design trends and principles that will dominate in 2024.',
                'content' => 'Full blog content here...',
                'featured_image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&q=80',
                'reading_time' => 6,
                'published_at' => Carbon::now()->subDays(8),
                'is_published' => true,
                'user_id' => $user->id,
                'category_id' => Category::where('slug', 'ui-ux-design')->first()->id,
                'tags' => ['ui-design', 'ux', 'tailwind-css'],
            ],
        ];

        foreach ($blogs as $blogData) {
            $tags = $blogData['tags'];
            unset($blogData['tags']);

            $blog = Blog::create($blogData);

            // Attach tags
            $tagIds = Tag::whereIn('slug', $tags)->pluck('id');
            $blog->tags()->attach($tagIds);
        }
    }
}
