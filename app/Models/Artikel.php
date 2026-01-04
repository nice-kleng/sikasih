<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'artikel';

    protected $fillable = [
        'penulis_id',
        'judul',
        'slug',
        'excerpt',
        'konten',
        'gambar_utama',
        'kategori',
        'tags',
        'views',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Boot method untuk auto generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });

        static::updating(function ($artikel) {
            if ($artikel->isDirty('judul') && empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });
    }

    /**
     * Relationships
     */

    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    /**
     * Scopes
     */

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopePopular($query, $limit = 5)
    {
        return $query->orderBy('views', 'desc')->limit($limit);
    }

    public function scopeTerbaru($query, $limit = 5)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    /**
     * Accessors
     */

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->konten));
        $minutes = ceil($words / 200); // Asumsi 200 kata per menit

        return $minutes . ' menit';
    }

    public function getTagsArrayAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    /**
     * Methods
     */

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now()
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'status' => 'draft'
        ]);
    }
}
