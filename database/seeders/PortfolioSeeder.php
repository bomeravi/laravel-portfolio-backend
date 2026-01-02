<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PortfolioItem::create([
            'title' => 'Brand Identity Design',
            'category' => 'Design',
            'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&q=80',
            'description' => 'Complete brand identity design including logo, color palette, and brand guidelines.',
            'tags' => ['Logo Design', 'Branding', 'Visual Identity'],
        ]);

        PortfolioItem::create([
            'title' => 'Mobile App UI',
            'category' => 'UI/UX',
            'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&q=80',
            'description' => 'User interface design for a mobile application with focus on user experience.',
            'tags' => ['Mobile Design', 'User Interface', 'UX Research'],
        ]);

        PortfolioItem::create([
            'title' => 'Website Redesign',
            'category' => 'Web',
            'image' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=800&q=80',
            'description' => 'Complete website redesign with modern aesthetics and improved user flow.',
            'tags' => ['Web Design', 'Responsive', 'User Experience'],
        ]);

        PortfolioItem::create([
            'title' => 'Dashboard Interface',
            'category' => 'UI/UX',
            'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80',
            'description' => 'Admin dashboard interface with data visualization and intuitive navigation.',
            'tags' => ['Dashboard', 'Data Visualization', 'Admin Panel'],
        ]);

        PortfolioItem::create([
            'title' => 'Logo Collection',
            'category' => 'Design',
            'image' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=800&q=80',
            'description' => 'Collection of logos created for various clients across different industries.',
            'tags' => ['Logo Design', 'Brand Identity', 'Vector Graphics'],
        ]);

        PortfolioItem::create([
            'title' => 'E-Commerce Design',
            'category' => 'Web',
            'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
            'description' => 'E-commerce website design with product catalog and shopping cart functionality.',
            'tags' => ['E-commerce', 'Product Design', 'Checkout Flow'],
        ]);
    }
}
