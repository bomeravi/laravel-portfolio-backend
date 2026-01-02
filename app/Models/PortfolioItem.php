<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'image',
        'description',
        'tags',
        'status',
        'featured_homepage',
    ];

    protected $casts = [
        'tags' => 'array',
        'status' => 'boolean',
        'featured_homepage' => 'boolean',
    ];
}
