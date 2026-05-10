<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id', 'dokter_id', 'layanan_id', 'tanggal',
        'no_antrian', 'keluhan', 'status', 'catatan_perawat',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function pasien() { return $this->belongsTo(Pasien::class); }
    public function dokter() { return $this->belongsTo(Dokter::class); }
    public function layanan() { return $this->belongsTo(Layanan::class); }
    public function rekamMedis() { return $this->hasOne(RekamMedis::class); }
    public function pembayaran() { return $this->hasOne(Pembayaran::class); }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'menunggu'  => 'warning',
            'dipanggil' => 'info',
            'selesai'   => 'success',
            'batal'     => 'danger',
            default     => 'neutral',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'  => 'Menunggu',
            'dipanggil' => 'Dipanggil',
            'selesai'   => 'Selesai',
            'batal'     => 'Dibatalkan',
            default     => ucfirst($this->status),
        };
    }
}
