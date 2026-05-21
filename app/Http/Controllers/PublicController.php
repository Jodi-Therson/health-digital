<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Fasilitas;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $layanans     = Layanan::aktif()->take(6)->get();
        $fasilitas    = Fasilitas::aktif()->take(4)->get();
        $dokters      = Dokter::with('user')->take(4)->get();
        $totalPasien  = User::where('role', 'pasien')->count();
        $totalDokter  = User::where('role', 'dokter')->count();
        $totalLayanan = Layanan::aktif()->count();
        return view('public.home', compact('layanans', 'fasilitas', 'dokters', 'totalPasien', 'totalDokter', 'totalLayanan'));
    }

    public function layanan()
    {
        $layanans = Layanan::aktif()->get();
        return view('public.layanan', compact('layanans'));
    }

    public function fasilitas()
    {
        $fasilitas = Fasilitas::aktif()->get();
        return view('public.fasilitas', compact('fasilitas'));
    }

    public function kontak()
    {
        return view('public.kontak');
    }
}

