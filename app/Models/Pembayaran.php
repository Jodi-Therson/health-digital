<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Str;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'antrian_id', 'konsultasi_id', 'pasien_id', 'kode_invoice', 'reference', 'jumlah',
        'metode', 'status', 'bukti_bayar', 'catatan', 'alasan_tolak',
        'dibayar_pada', 'verified_by',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->reference)) {
                $model->reference = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'dibayar_pada' => 'datetime',
            'jumlah' => 'decimal:2',
        ];
    }

    public function antrian()     { return $this->belongsTo(Antrian::class); }
    public function pasien()       { return $this->belongsTo(Pasien::class); }
    public function konsultasi()   { return $this->belongsTo(Konsultasi::class); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu' => 'Menunggu Pembayaran',
            'dibayar'  => 'Lunas',
            'gagal'    => 'Gagal',
            'dikembalikan' => 'Dikembalikan',
            default    => ucfirst($this->status),
        };
    }

    public function getJenisAttribute(): string
    {
        return $this->konsultasi_id ? 'konsultasi' : 'antrian';
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'menunggu' => 'warning',
            'dibayar'  => 'success',
            default    => 'neutral',
        };
    }

    public function getMetodeLabelAttribute(): string
    {
        return 'QRIS';
    }

    public function getJumlahFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }
}
