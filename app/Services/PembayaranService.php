<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Antrian;
use Carbon\Carbon;

class PembayaranService
{
    public function generateInvoice(): string
    {
        $tgl = Carbon::now()->format('Ymd');
        $last = Pembayaran::whereDate('created_at', today())->count() + 1;
        return 'INV-' . $tgl . '-' . str_pad($last, 6, '0', STR_PAD_LEFT);
    }

    public function buatTagihan(Antrian $antrian, string $metode): Pembayaran
    {
        $tarif = $antrian->dokter->tarif_konsultasi ?? 150000;
        
        return Pembayaran::create([
            'antrian_id'   => $antrian->id,
            'pasien_id'    => $antrian->pasien_id,
            'kode_invoice' => $this->generateInvoice(),
            'jumlah'       => $tarif,
            'metode'       => $metode,
            'status'       => $metode === 'bpjs' ? 'dibayar' : 'menunggu',
            'dibayar_pada' => $metode === 'bpjs' ? now() : null,
        ]);
    }

}

