@extends('layouts.public')
@section('title', 'Kontak Kami')
@section('content')
<div style="background:linear-gradient(135deg,#1e40af,#2563eb); padding:3rem 1.5rem; text-align:center;">
    <div style="max-width:1280px; margin:0 auto;">
        <h1 style="font-size:2rem; font-weight:800; color:white; margin-bottom:0.75rem;">Hubungi Kami</h1>
        <p style="color:rgba(255,255,255,0.85);">Kami siap membantu pertanyaan dan kebutuhan kesehatan Anda</p>
    </div>
</div>
<section style="padding:3rem 1.5rem;">
    <div style="max-width:1024px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:start;" class="kontak-grid">
        <div>
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-body">
                    <h2 style="font-size:1.125rem; font-weight:700; color:#1e293b; margin-bottom:1.25rem;">Informasi Kontak</h2>
                    @php $contacts = [
                        ['icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z','label'=>'Alamat','val'=>'Jl. Kesehatan No. 1, Jakarta Pusat 10110'],
                        ['icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z','label'=>'Telepon','val'=>'(021) 123-4567'],
                        ['icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z','label'=>'Email','val'=>'info@healthdigital.id'],
                        ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Jam Layanan','val'=>'Senin–Jumat: 07.00–17.00 | Sabtu: 07.00–12.00'],
                    ]; @endphp
                    @foreach($contacts as $c)
                    <div style="display:flex; gap:0.875rem; margin-bottom:1rem;">
                        <div style="background:#dbeafe; width:2.5rem; height:2.5rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg style="width:1.125rem;height:1.125rem;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"/></svg>
                        </div>
                        <div>
                            <div style="font-size:0.75rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">{{ $c['label'] }}</div>
                            <div style="font-size:0.875rem; color:#334155; margin-top:0.125rem;">{{ $c['val'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Emergency -->
            <div style="background:linear-gradient(135deg,#fee2e2,#fecaca); border:1px solid #fca5a5; border-radius:0.875rem; padding:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
                    <div style="background:#ef4444; width:2rem; height:2rem; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        <svg style="width:1rem;height:1rem;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div style="font-weight:700; color:#991b1b;">Gawat Darurat (IGD)</div>
                </div>
                <div style="font-size:1.5rem; font-weight:800; color:#dc2626;">119 ext 1</div>
                <div style="font-size:0.8125rem; color:#b91c1c; margin-top:0.25rem;">Layanan darurat 24 jam / 7 hari</div>
            </div>
        </div>

        <!-- Form -->
        <div class="card">
            <div class="card-header">Kirim Pesan</div>
            <div class="card-body">
                <form x-data="{ loading: false }" @submit.prevent="loading = true; setTimeout(() => { loading = false; $el.reset(); alert('Pesan berhasil dikirim!'); }, 1500);">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-input" placeholder="Nama Anda" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" placeholder="email@domain.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <input type="text" class="form-input" placeholder="Topik pesan Anda" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-input" placeholder="Tuliskan pesan atau pertanyaan Anda..." rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;" :disabled="loading">
                        <span x-show="!loading">Kirim Pesan</span>
                        <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><span class="spinner"></span>Mengirim...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<style>@media(max-width:768px){.kontak-grid{grid-template-columns:1fr !important;}}</style>
@endsection
