@extends('layouts.app')
@section('title', 'Laporan')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Laporan Sistem</h1><p class="page-subtitle">Pilih rentang tanggal untuk mengunduh laporan bulanan</p></div>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-header">Unduh Laporan Format PDF</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.export') }}" target="_blank">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
                <div class="form-group"><label class="form-label">Tanggal Mulai <span style="color:#ef4444;">*</span></label><input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Tanggal Akhir <span style="color:#ef4444;">*</span></label><input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" class="form-input" required></div>
            </div>
            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-primary" style="display:flex;align-items:center;gap:0.5rem;">
                    <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Generate PDF
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
