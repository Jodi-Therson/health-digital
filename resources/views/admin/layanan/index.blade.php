@extends('layouts.app')
@section('title', 'Manajemen Layanan')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Manajemen Layanan</h1><p class="page-subtitle">Kelola poli dan layanan kesehatan</p></div>
    <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary">+ Tambah Layanan</a>
</div>
<div class="card">
    @if($layanans->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Belum ada data layanan</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">Urutan</th>
                    <th style="width:50px;">Ikon</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($layanans as $l)
                <tr>
                    <td style="text-align:center; font-weight:700; color:#64748b;">#{{ $l->urutan }}</td>
                    <td>
                        <div style="background:#f1f5f9; width:2.25rem; height:2.25rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; color:#3b82f6;">
                            @php
                                $iconMap = [
                                    'stethoscope' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                    'tooth' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
                                    'baby' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                                    'heart' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                    'bolt' => 'M13 10V3L4 14h7v7l9-11h-7z'
                                ];
                                $iconPath = $iconMap[$l->ikon] ?? $l->ikon ?? 'M13 10V3L4 14h7v7l9-11h-7z';
                            @endphp
                            <svg style="width:1.125rem;height:1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/></svg>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600; color:#1e293b;">{{ $l->nama }}</div>
                    </td>
                    <td><div style="max-width:300px; font-size:0.8125rem; color:#64748b;">{{ Str::limit($l->deskripsi, 80) }}</div></td>
                    <td>
                        <form method="POST" action="{{ route('admin.layanan.update', $l->id) }}" x-ref="toggleForm{{ $l->id }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="nama" value="{{ $l->nama }}">
                            <input type="hidden" name="urutan" value="{{ $l->urutan }}">
                            <input type="hidden" name="is_active" value="{{ $l->is_active ? '0' : '1' }}">
                            
                            <button type="button" 
                                @click="triggerConfirm(
                                    '{{ $l->is_active ? 'Nonaktifkan Layanan' : 'Aktifkan Layanan' }}',
                                    '{{ $l->is_active ? 'Pasien tidak bisa mendaftar antrian layanan ini setelah dinonaktifkan.' : 'Pasien dapat kembali mendaftar antrian untuk layanan ini.' }}',
                                    () => { $refs.toggleForm{{ $l->id }}.submit() },
                                    '{{ $l->is_active ? 'danger' : 'primary' }}'
                                )"
                                class="badge {{ $l->is_active ? 'badge-success' : 'badge-danger' }}"
                                style="border:none; cursor:pointer;">
                                {{ $l->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                            <a href="{{ route('admin.layanan.edit', $l->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.layanan.destroy', $l->id) }}" x-ref="deleteForm{{ $l->id }}">
                                @csrf @method('DELETE')
                                <button type="button" 
                                    @click="triggerConfirm(
                                        'Hapus Layanan',
                                        'Anda yakin ingin menghapus layanan {{ $l->nama }}? Tindakan ini tidak dapat dibatalkan.',
                                        () => { $refs.deleteForm{{ $l->id }}.submit() },
                                        'danger'
                                    )"
                                    class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $layanans->links() }}</div>
    @endif
</div>
@endsection
