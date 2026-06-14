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
                <li style="margin-bottom:0.5rem;">Pilih <strong>Layanan (Poliklinik)</strong> yang Anda tuju terlebih dahulu. Sistem kami secara otomatis memfilter daftar dokter aktif yang terafiliasi dengan poliklinik tersebut.</li>
                <li style="margin-bottom:0.5rem;">Klik kolom <strong>Dokter</strong> untuk membuka pemilih dokter visual interaktif. Anda dapat **mengenali dokter langsung melalui foto profil asli, melihat spesialisasi, serta membandingkan tarif konsultasi** secara real-time.</li>
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
                <li>Setelah Anda menyelesaikan transfer pada aplikasi Anda, <strong>sistem akan mendeteksi dan memverifikasi pembayaran secara otomatis</strong>.</li>
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
                <li style="margin-bottom:0.5rem;">Pilih <strong>Layanan (Poliklinik)</strong> terlebih dahulu, kemudian klik kolom <strong>Dokter</strong> untuk memilih dokter yang Anda inginkan menggunakan <strong>pemilih visual interaktif lengkap dengan foto profil asli dan tarif</strong>.</li>
                <li style="margin-bottom:0.5rem;">Tulis judul dan jelaskan keluhan Anda sedetail mungkin (minimal 20 karakter).</li>
                <li style="margin-bottom:0.5rem;">Anda akan dialihkan ke halaman pembayaran untuk menyelesaikan pembayaran dahulu sebelum dokter dapat membalas pesan anda.</li>
                <li style="margin-bottom:0.5rem;">Setelah membayar, dokter akan segera membalas pesan anda, Anda bisa membalas kembali pesan tersebut hingga dokter menutup sesi konsultasi.</li>
            </ol>
        </div>
    </div>

    <!-- FAQ 4: Melihat Resep Obat -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">4. Bagaimana cara melihat Resep Obat setelah pemeriksaan selesai?</div>
        <div class="card-body" style="color:#475569;line-height:1.6;font-size:0.9375rem;">
            <p style="margin-bottom:0.75rem;">Setelah pemeriksaan fisik Anda oleh dokter selesai, resep obat yang diberikan dapat dilihat secara digital:</p>
            <ol style="padding-left:1.5rem;margin-bottom:0;">
                <li style="margin-bottom:0.5rem;">Buka menu <strong>Antrian Saya</strong>.</li>
                <li style="margin-bottom:0.5rem;">Cari antrian pemeriksaan yang berstatus <span class="badge badge-success">Selesai</span>.</li>
                <li>Klik tombol <strong>Detail</strong>. Anda akan melihat rincian <strong>Resep Obat Dokter</strong> lengkap dengan nama obat, dosis, dan aturan pakainya secara langsung.</li>
            </ol>
        </div>
    </div>

    <!-- FAQ 5: Hubungi Kami -->
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">5. Hubungi Kami</div>
    <div class="card-body" style="color:#475569;line-height:1.6;font-size:0.9375rem;">
        <p style="margin-bottom:0.75rem;">
            Jika Anda mengalami kendala saat menggunakan portal pasien, membutuhkan bantuan pendaftaran,
            pembayaran, atau memiliki pertanyaan terkait layanan kesehatan, silakan hubungi kami melalui:
        </p>

        <ul style="padding-left:1.5rem;margin-bottom:1rem;">
            <li style="margin-bottom:0.5rem;">
                <strong>Alamat:</strong> Jl. Kesehatan No. 1, Jakarta Pusat 10110
            </li>
            <li style="margin-bottom:0.5rem;">
                <strong>Telepon:</strong> (021) 123-4567
            </li>
            <li style="margin-bottom:0.5rem;">
                <strong>Email:</strong> info@healthdigital.id
            </li>
            <li>
                <strong>Jam Operasional:</strong> Senin-Jumat: 07.00-17.00 WIB<br>
                Sabtu: 07.00-12.00 WIB
            </li>
        </ul>

        <div style="padding:0.75rem;background:#f8fafc;border-left:4px solid #10b981;border-radius:0.25rem;">
            <strong>Tips:</strong> Untuk mendapatkan bantuan lebih cepat, siapkan nomor antrian,
            nomor tagihan, atau informasi akun Anda saat menghubungi petugas kami.
        </div>
    </div>
</div>

</div>
@endsection
