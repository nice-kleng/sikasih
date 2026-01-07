<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoEdukasi extends Model
{
    use HasFactory;

    protected $table = 'video_edukasi';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'youtube_id',
        'thumbnail',
        'kategori',
        'durasi_detik',
        'views',
        'urutan',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];
}
