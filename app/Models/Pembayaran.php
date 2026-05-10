<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'antrian_id', 'pasien_id', 'kode_invoice', 'jumlah',
        'metode', 'status', 'bukti_bayar', 'catatan', 'dibayar_pada', 'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'dibayar_pada' => 'datetime',
            'jumlah' => 'decimal:2',
        ];
    }

    public function antrian() { return $this->belongsTo(Antrian::class); }
    public function pasien() { return $this->belongsTo(Pasien::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'     => 'Menunggu Pembayaran',
            'dibayar'      => 'Lunas',
            'gagal'        => 'Gagal',
            'dikembalikan' => 'Dikembalikan',
            default        => ucfirst($this->status),
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'menunggu'     => 'warning',
            'dibayar'      => 'success',
            'gagal'        => 'danger',
            'dikembalikan' => 'info',
            default        => 'neutral',
        };
    }

    public function getMetodeLabelAttribute(): string
    {
        return match($this->metode) {
            'bpjs'     => 'BPJS',
            'transfer' => 'Transfer Bank',
            'tunai'    => 'Tunai',
            'qris'     => 'QRIS',
            default    => strtoupper($this->metode),
        };
    }

    public function getJumlahFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }
}
