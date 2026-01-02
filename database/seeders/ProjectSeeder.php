<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'title' => 'E-Commerce Platform',
            'description' => 'A full-featured online shopping platform with payment integration, inventory management, and admin dashboard.',
            'image' => 'https://images.unsplash.com/photo-1557821552-17105176677c?w=800&q=80',
            'tags' => ['React', 'Node.js', 'MongoDB', 'Stripe'],
            'live_url' => 'https://example.com',
            'github_url' => 'https://github.com',
        ]);

        Project::create([
            'title' => 'Task Management App',
            'description' => 'Collaborative task management tool with real-time updates, team chat, and project tracking features.',
            'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=80',
            'tags' => ['React', 'Firebase', 'Tailwind CSS'],
            'live_url' => 'https://example.com',
            'github_url' => 'https://github.com',
        ]);

        Project::create([
            'title' => 'Weather Dashboard',
            'description' => 'Beautiful weather application with forecasts, maps, and location-based weather alerts.',
            'image' => 'https://images.unsplash.com/photo-1592210454359-9043f067919b?w=800&q=80',
            'tags' => ['React', 'OpenWeather API', 'Chart.js'],
            'live_url' => 'https://example.com',
            'github_url' => 'https://github.com',
        ]);
    }
}
