<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'tags',
        'live_url',
        'github_url',
        'status',
        'list_order',
        'status',
        'featured_homepage',
    ];

    protected $casts = [
        'tags' => 'array',
        'status' => 'boolean',
        'featured_homepage' => 'boolean',
    ];
}
