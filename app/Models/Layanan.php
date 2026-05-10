<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'ikon', 'is_active', 'urutan'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function antrians() { return $this->hasMany(Antrian::class); }

    public function scopeAktif($query) { return $query->where('is_active', true)->orderBy('urutan'); }
}
