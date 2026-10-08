<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Publication extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'body',
        'image',
        'excerpt',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Publication $pub) {
            if (empty($pub->slug)) {
                $pub->slug = Str::slug($pub->title);
            }
            if (empty($pub->published_at)) {
                $pub->published_at = now();
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeBerita($query)
    {
        return $query->where('type', 'berita');
    }

    public function scopeInfografis($query)
    {
        return $query->where('type', 'infografis');
    }

    /**
     * Get image URL (from local storage).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        return asset('storage/' . $this->image);
    }
}
