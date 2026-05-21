<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'ikon', 'gambar', 'is_active', 'urutan'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function antrians() { return $this->hasMany(Antrian::class); }

    public function scopeAktif($query) { return $query->where('is_active', true)->orderBy('urutan'); }

    /**
     * URL gambar layanan (dari storage), atau null jika belum ada.
     */
    public function getGambarUrlAttribute(): ?string
    {
        if ($this->gambar) {
            return Storage::url($this->gambar);
        }
        return null;
    }
}
