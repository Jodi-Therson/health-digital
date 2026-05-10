<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesanKonsultasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'konsultasi_id', 'pengirim', 'pesan',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class);
    }
}
