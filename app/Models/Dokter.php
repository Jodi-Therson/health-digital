<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'no_str', 'spesialisasi', 'jadwal', 'tarif_konsultasi', 'bio',
    ];

    protected function casts(): array
    {
        return [
            'jadwal' => 'array',
            'tarif_konsultasi' => 'decimal:2',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function antrians() { return $this->hasMany(Antrian::class); }
    public function rekamMedis() { return $this->hasMany(RekamMedis::class); }
    public function konsultasis() { return $this->hasMany(Konsultasi::class); }

    public function getNameAttribute(): string
    {
        return $this->user->name ?? '';
    }
}
