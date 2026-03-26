<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ELibraryDocument extends Model
{
    use HasFactory;

    protected $table = 'e_library_documents';

    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';
    const STATUS_PRIVATE = 'private';

    protected $fillable = [
        'title', 'slug', 'description', 'pdf_path', 'cover_path', 'status', 'position',
        'downloads_count', 'views_count'
    ];

    protected $casts = [
        'downloads_count' => 'integer',
        'views_count' => 'integer',
    ];

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPrivate(): bool
    {
        return $this->status === self::STATUS_PRIVATE;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($doc) {
            $doc->slug = static::generateUniqueSlug($doc->title);
            $doc->position = static::getNextPosition();
        });
    }

    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }

    public static function getNextPosition()
    {
        return (static::max('position') ?? 0) + 1;
    }
}
