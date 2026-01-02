<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'start_date',
        'end_date',
        'current',
        'description',
        'achievements',
        'display_month',
        'status',
    ];

    protected $casts = [
        'achievements' => 'array',
        'current' => 'boolean',
        'display_month' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    protected $appends = ['period_display'];

    public function getPeriodDisplayAttribute()
    {
        $startFormat = $this->display_month ? 'F Y' : 'Y';
        $start = $this->start_date->format($startFormat);

        if ($this->current || !$this->end_date) {
            $end = 'Present';
        } else {
            $end = $this->end_date->format($startFormat);
        }

        return "{$start} - {$end}";
    }
}
