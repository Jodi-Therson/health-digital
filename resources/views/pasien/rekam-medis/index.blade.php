@extends('layouts.app')
@section('title', 'Rekam Medis')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span><span>Rekam Medis</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Rekam Medis Saya</h1><p class="page-subtitle">Riwayat pemeriksaan dan diagnosa</p></div>
</div>
<div class="card">
    @if($rekamMedis->isEmpty())
    <div class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <div style="font-weight:600;color:#94a3b8;">Belum ada rekam medis</div>
    </div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Tanggal</th><th>Dokter</th><th>Layanan</th><th>Diagnosa</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($rekamMedis as $rm)
                <tr>
                    <td>{{ $rm->tanggal_periksa->format('d M Y') }}</td>
                    <td>{{ $rm->dokter->user->name }}</td>
                    <td>{{ $rm->antrian?->layanan?->nama ?? '—' }}</td>
                    <td>{{ Str::limit($rm->diagnosa, 50) }}</td>
                    <td><a href="{{ route('pasien.rekam-medis.show', $rm->id) }}" class="btn btn-secondary btn-sm">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $rekamMedis->links() }}</div>
    @endif
</div>
@endsection
