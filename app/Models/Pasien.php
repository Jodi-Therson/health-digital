<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nik', 'no_bpjs', 'tanggal_lahir', 'jenis_kelamin',
        'golongan_darah', 'alamat', 'kota', 'pekerjaan',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function antrians() { return $this->hasMany(Antrian::class); }
    public function rekamMedis() { return $this->hasMany(RekamMedis::class); }
    public function konsultasis() { return $this->hasMany(Konsultasi::class); }
    public function pembayarans() { return $this->hasMany(Pembayaran::class); }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }
}
