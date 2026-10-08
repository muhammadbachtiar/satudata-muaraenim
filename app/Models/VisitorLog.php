<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VisitorLog extends Model
{
    protected $fillable = ['ip_address', 'page', 'user_agent', 'visited_date'];

    /**
     * Log a visit (one per IP per day)
     */
    public static function logVisit(string $ip, string $page, ?string $userAgent): void
    {
        $today = now()->toDateString();

        $exists = static::where('ip_address', $ip)
            ->where('visited_date', $today)
            ->exists();

        if (!$exists) {
            static::create([
                'ip_address'   => $ip,
                'page'         => $page,
                'user_agent'   => $userAgent ? mb_substr($userAgent, 0, 500) : null,
                'visited_date' => $today,
            ]);
        }
    }

    /**
     * Get visitor statistics
     */
    public static function getStats(): array
    {
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();
        $startOfYear = now()->startOfYear()->toDateString();

        return [
            'total'      => static::count(),
            'today'      => static::where('visited_date', $today)->count(),
            'this_month' => static::where('visited_date', '>=', $startOfMonth)->count(),
            'this_year'  => static::where('visited_date', '>=', $startOfYear)->count(),
        ];
    }
}
