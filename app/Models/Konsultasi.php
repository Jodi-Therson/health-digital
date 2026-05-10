<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Konsultasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id', 'dokter_id', 'judul', 'pesan', 'balasan',
        'status', 'dibaca_dokter', 'dibaca_pasien',
    ];

    protected function casts(): array
    {
        return [
            'dibaca_dokter' => 'boolean',
            'dibaca_pasien' => 'boolean',
        ];
    }

    public function pasien() { return $this->belongsTo(Pasien::class); }
    public function dokter() { return $this->belongsTo(Dokter::class); }
    public function pesans() { return $this->hasMany(PesanKonsultasi::class); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu' => 'Menunggu',
            'dijawab'  => 'Dijawab',
            'ditutup'  => 'Ditutup',
            default    => ucfirst($this->status),
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'menunggu' => 'warning',
            'dijawab'  => 'success',
            'ditutup'  => 'neutral',
            default    => 'neutral',
        };
    }
}
