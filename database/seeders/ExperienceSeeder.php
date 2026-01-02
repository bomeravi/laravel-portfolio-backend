<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Experience::create([
            'title' => 'Senior Full Stack Developer',
            'company' => 'Tech Solutions Inc.',
            'start_date' => '2022-03-01',
            'end_date' => null,
            'current' => true,
            'description' => 'Leading development of enterprise web applications using React, Node.js, and cloud technologies. Mentoring junior developers and implementing best practices.',
            'achievements' => [
                'Reduced application load time by 40%',
                'Led team of 5 developers',
                'Implemented CI/CD pipeline'
            ],
            'display_month' => true,
        ]);

        Experience::create([
            'title' => 'Full Stack Developer',
            'company' => 'Digital Innovations',
            'start_date' => '2020-01-15',
            'end_date' => '2022-02-28',
            'current' => false,
            'description' => 'Developed and maintained multiple client projects using modern web technologies. Collaborated with designers and product managers to deliver high-quality solutions.',
            'achievements' => [
                'Built 15+ client projects',
                'Improved code quality by 30%',
                'Implemented responsive designs'
            ],
            'display_month' => false,
        ]);

        Experience::create([
            'title' => 'Frontend Developer',
            'company' => 'StartUp Labs',
            'start_date' => '2019-06-01',
            'end_date' => '2019-12-31',
            'current' => false,
            'description' => 'Created responsive user interfaces and implemented interactive features for web applications. Worked closely with UX designers to ensure pixel-perfect implementations.',
            'achievements' => [
                'Developed reusable component library',
                'Optimized performance',
                'Collaborated with cross-functional teams'
            ],
            'display_month' => true,
        ]);
    }
}
