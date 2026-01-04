<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanKonsultasi extends Model
{
    use HasFactory;

    protected $table = 'pesan_konsultasi';

    protected $fillable = [
        'konsultasi_id',
        'pengirim_id',
        'tipe_pengirim',
        'pesan',
        'file_lampiran',
        'tipe_pesan',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_pada');
    }

    public function scopeSudahDibaca($query)
    {
        return $query->whereNotNull('dibaca_pada');
    }
}
