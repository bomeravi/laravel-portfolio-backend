<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'blogs' => [
                'total' => Blog::count(),
                'published' => Blog::where('is_published', true)->count(),
                'draft' => Blog::where('is_published', false)->count(),
            ],
            'messages' => [
                'total' => ContactMessage::count(),
                'new' => ContactMessage::where('status', 'pending')->orWhereNull('status')->count(),
            ],
            'users' => User::count(),
            'projects' => Project::count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
