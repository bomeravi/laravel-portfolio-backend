<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $skills = [
            ['name' => 'React', 'level' => 95, 'tags' => ['Frontend']],
            ['name' => 'TypeScript', 'level' => 90, 'tags' => ['Frontend']],
            ['name' => 'Javascript', 'level' => 92, 'tags' => ['Frontend']],
            ['name' => 'HTML/CSS', 'level' => 95, 'tags' => ['Frontend']],
            ['name' => 'Tailwind CSS', 'level' => 88, 'tags' => ['Frontend']],
            ['name' => 'Node.js', 'level' => 80, 'tags' => ['Backend']],
            ['name' => 'Express', 'level' => 85, 'tags' => ['Backend']],
            ['name' => 'PostgresSQL', 'level' => 88, 'tags' => ['Backend']],
            ['name' => 'MongoDB', 'level' => 82, 'tags' => ['Backend']],
            ['name' => 'Rest Apis', 'level' => 90, 'tags' => ['Backend']],
            ['name' => 'GraphQL', 'level' => 70, 'tags' => ['Backend']],
            ['name' => 'Git', 'level' => 92, 'tags' => ['Tools']],
            ['name' => 'Docker', 'level' => 75, 'tags' => ['Tools']],
            ['name' => 'AWS', 'level' => 70, 'tags' => ['Server']],
            ['name' => 'Postman', 'level' => 88, 'tags' => ['Tools']],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
