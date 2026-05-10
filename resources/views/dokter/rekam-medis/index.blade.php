@extends('layouts.app')
@section('title', 'Rekam Medis Pasien')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Rekam Medis Pasien</h1></div>
    <a href="{{ route('dokter.rekam-medis.create') }}" class="btn btn-primary">+ Buat Rekam Medis</a>
</div>
<div class="card" style="margin-bottom:1rem;">
    <div class="card-body" style="padding:1rem;">
        <form method="GET" style="display:flex;gap:1rem;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Cari nama pasien..." style="max-width:300px;">
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        </form>
    </div>
</div>
<div class="card">
    @if($rekamMedis->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada rekam medis</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Tanggal</th><th>Pasien</th><th>Diagnosa</th><th>Vital Signs</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($rekamMedis as $rm)
                <tr>
                    <td>{{ $rm->tanggal_periksa->format('d M Y') }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $rm->pasien->user->name }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ optional($rm->antrian->layanan)->nama }}</div>
                    </td>
                    <td>{{ Str::limit($rm->diagnosa, 50) }}</td>
                    <td>
                        <div style="font-size:0.75rem;color:#64748b;">
                            @if($rm->tekanan_darah)
                                TD: {{ $rm->tekanan_darah }}
                            @endif
                            @if($rm->suhu_tubuh)
                                • {{ $rm->suhu_tubuh }}°C
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('dokter.rekam-medis.show', $rm->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="{{ route('dokter.rekam-medis.edit', $rm->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $rekamMedis->links() }}</div>
    @endif
</div>
@endsection
