<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'layanan_id',
        'user_id',
        'nama_pelanggan',
        'no_hp',
        'berat',
        'total_harga',
        'tanggal_masuk',
        'status',
    ];

    protected $casts = [
        'berat' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tanggal_masuk' => 'date',
    ];

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
