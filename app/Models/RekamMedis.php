<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'antrian_id', 'pasien_id', 'dokter_id', 'tanggal_periksa',
        'anamnesis', 'diagnosa', 'tindakan', 'resep',
        'tekanan_darah', 'berat_badan', 'tinggi_badan', 'suhu_tubuh', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_periksa' => 'date',
            'resep' => 'array',
        ];
    }

    public function antrian() { return $this->belongsTo(Antrian::class); }
    public function pasien() { return $this->belongsTo(Pasien::class); }
    public function dokter() { return $this->belongsTo(Dokter::class); }
}
