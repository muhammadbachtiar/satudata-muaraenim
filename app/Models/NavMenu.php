<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavMenu extends Model
{
    protected $fillable = [
        'title',
        'type',
        'url',
        'route_name',
        'page_id',
        'parent_id',
        'order',
        'is_active',
        'open_in_new_tab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavMenu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavMenu::class, 'parent_id')->orderBy('order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the resolved URL for this menu item.
     */
    public function getResolvedUrlAttribute(): string
    {
        return match ($this->type) {
            'link' => $this->url ?? '#',
            'page' => $this->page ? '/halaman/' . $this->page->slug : '#',
            'route' => $this->route_name ? (route($this->route_name, [], false) ?? '#') : '#',
            default => '#',
        };
    }

    /**
     * Scope: only top-level (no parent) active menus.
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->where('is_active', true)->orderBy('order');
    }
}
