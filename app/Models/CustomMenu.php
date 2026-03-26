<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomMenu extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'builtin_parent', 'icon', 'position', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($menu) {
            if (empty($menu->slug)) {
                $menu->slug = Str::slug($menu->name);
                $orig = $menu->slug;
                $i = 1;
                while (static::where('slug', $menu->slug)->exists()) {
                    $menu->slug = $orig . '-' . $i++;
                }
            }
        });
    }

    // Parent menu
    public function parent()
    {
        return $this->belongsTo(CustomMenu::class, 'parent_id');
    }

    // Sub menus
    public function children()
    {
        return $this->hasMany(CustomMenu::class, 'parent_id')->orderBy('position');
    }

    // Widgets (form fields)
    public function widgets()
    {
        return $this->hasMany(CustomMenuWidget::class, 'menu_id')->orderBy('position');
    }

    // Active widgets only
    public function activeWidgets()
    {
        return $this->hasMany(CustomMenuWidget::class, 'menu_id')->where('is_active', true)->orderBy('position');
    }

    // Scope: top-level only (exclude built-in attached menus)
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->whereNull('builtin_parent');
    }

    // Scope: menus attached to a specific built-in nav section
    public function scopeForBuiltin($query, string $key)
    {
        return $query->where('builtin_parent', $key)->whereNull('parent_id');
    }

    // Scope: published
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope: ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    // Full URL for the public page
    public function getUrlAttribute()
    {
        if ($this->parent_id) {
            $parent = $this->parent;
            if ($parent && $parent->id !== $this->id) {
                return route('custom.page', ['slug' => $parent->slug, 'childSlug' => $this->slug]);
            }
        }
        // Top-level with children → first child or own page
        $firstChild = $this->children()->first();
        if ($firstChild) {
            return route('custom.page', ['slug' => $this->slug, 'childSlug' => $firstChild->slug]);
        }
        return route('custom.page', ['slug' => $this->slug]);
    }
}
