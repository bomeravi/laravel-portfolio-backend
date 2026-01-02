<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactMessage extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'token',
        'status',
        'ip',
    ];

    /**
     * Generate a unique token
     */
    public static function generateToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32)) . microtime();
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Mark contact as processed
     */
    public function markAsProcessed(): void
    {
        $this->update([
            'status' => 'completed',
        ]);
    }

    /**
     * Scope for recent contacts (last 24 hours)
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}
