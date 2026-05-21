@extends('layouts.app')
@section('title', 'Pusat Bantuan & Panduan')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pusat Bantuan</h1>
        <p class="page-subtitle">Panduan penggunaan portal pasien Health Digital</p>
    </div>
</div>

<div style="max-width:800px;">
    <!-- FAQ 1: Antrian -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">1. Bagaimana cara mendaftar antrian?</div>
        <div class="card-body" style="color:#475569;line-height:1.6;font-size:0.9375rem;">
            <p style="margin-bottom:0.75rem;">Untuk mendaftar antrian tatap muka di fasilitas kami:</p>
            <ol style="padding-left:1.5rem;margin-bottom:0;">
                <li style="margin-bottom:0.5rem;">Klik menu <strong>Antrian Saya</strong> di sidebar sebelah kiri.</li>
                <li style="margin-bottom:0.5rem;">Klik tombol biru <strong>Daftar Antrian Sekarang</strong>.</li>
                <li style="margin-bottom:0.5rem;">Pilih <strong>Layanan (Poliklinik)</strong> yang Anda tuju terlebih dahulu. Sistem kami secara otomatis memfilter daftar dokter aktif yang terafiliasi dengan poliklinik tersebut (Error Prevention).</li>
                <li style="margin-bottom:0.5rem;">Klik kolom <strong>Dokter</strong> untuk membuka pemilih dokter visual interaktif. Anda dapat **mengenali dokter langsung melalui foto profil asli, melihat spesialisasi, serta membandingkan tarif konsultasi** secara real-time (Recognition Rather Than Recall Heuristic).</li>
                <li style="margin-bottom:0.5rem;">Pilih Tanggal kunjungan dan tulis Keluhan yang Anda rasakan.</li>
                <li>Klik <strong>Buat Antrian</strong>. Periksa detailnya di layar konfirmasi lalu klik **Daftar Sekarang**. Tagihan pembayaran akan dibuat secara otomatis.</li>
            </ol>
            <div style="margin-top:1rem;padding:0.75rem;background:#f8fafc;border-left:4px solid #3b82f6;border-radius:0.25rem;">
                <strong>Catatan:</strong> Anda dapat membatalkan antrian kapan saja selama statusnya masih "Menunggu". Buka detail antrian lalu klik "Batalkan Antrian".
            </div>
        </div>
    </div>

    <!-- FAQ 2: Pembayaran -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">2. Cara melakukan pembayaran tagihan</div>
        <div class="card-body" style="color:#475569;line-height:1.6;font-size:0.9375rem;">
            <p style="margin-bottom:0.75rem;">Setiap kali Anda membuat antrian atau konsultasi online, sistem otomatis mencetak tagihan.</p>
            <ol style="padding-left:1.5rem;margin-bottom:0;">
                <li style="margin-bottom:0.5rem;">Buka menu <strong>Pembayaran</strong>.</li>
                <li style="margin-bottom:0.5rem;">Klik <strong>Bayar Sekarang</strong> pada tagihan yang berstatus "Menunggu".</li>
                <li style="margin-bottom:0.5rem;">Scan <strong>QRIS QR Code</strong> yang tampil di layar menggunakan aplikasi dompet digital Anda (GoPay, OVO, DANA, LinkAja, dll.) atau Mobile Banking pilihan Anda.</li>
                <li>Setelah Anda menyelesaikan transfer pada aplikasi Anda, <strong>sistem akan mendeteksi dan memverifikasi pembayaran secara otomatis</strong>. Halaman akan langsung dialihkan tanpa perlu mengunggah bukti transfer secara manual (Visibility of System Status Heuristic).</li>
            </ol>
        </div>
    </div>

    <!-- FAQ 3: Konsultasi -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">3. Menggunakan fitur Konsultasi Online (Chat)</div>
        <div class="card-body" style="color:#475569;line-height:1.6;font-size:0.9375rem;">
            <p style="margin-bottom:0.75rem;">Jika Anda tidak bisa datang langsung ke klinik, Anda dapat berkonsultasi jarak jauh:</p>
            <ol style="padding-left:1.5rem;margin-bottom:0;">
                <li style="margin-bottom:0.5rem;">Buka menu <strong>Konsultasi Online</strong> lalu klik <strong>+ Buat Konsultasi</strong>.</li>
                <li style="margin-bottom:0.5rem;">Pilih <strong>Layanan (Poliklinik)</strong> terlebih dahulu, kemudian klik kolom **Dokter** untuk memilih dokter yang Anda inginkan menggunakan **pemilih visual interaktif lengkap dengan foto profil asli dan tarif**.</li>
                <li style="margin-bottom:0.5rem;">Tulis judul dan jelaskan keluhan Anda sedetail mungkin (minimal 20 karakter).</li>
                <li style="margin-bottom:0.5rem;">Setelah dokter membalas, Anda bisa membalas kembali pesan tersebut hingga dokter menutup sesi konsultasi.</li>
            </ol>
        </div>
    </div>

    <!-- FAQ 4: Rekam Medis -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">4. Melihat Rekam Medis & Resep</div>
        <div class="card-body" style="color:#475569;line-height:1.6;font-size:0.9375rem;">
            <p>Setelah Anda selesai diperiksa oleh dokter di fasilitas kami, rekam medis akan tersimpan secara digital. Buka menu <strong>Rekam Medis</strong> untuk melihat riwayat diagnosa, tindakan, hingga rincian obat-obatan (resep) yang diberikan oleh dokter.</p>
        </div>
    </div>

</div>
@endsection
