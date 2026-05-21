@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Manajemen Pengguna</h1></div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah Pengguna</a>
</div>

<div class="card" style="margin-bottom: 1.5rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
    <div class="card-body" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('admin.users.index') }}" class="filter-grid">
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nama / Email..." style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box; width: 100%;">
            </div>
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Role</label>
                <select name="role" class="form-input" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box; width: 100%;">
                    <option value="">Semua Role</option>
                    <option value="pasien" {{ request('role')=='pasien'?'selected':'' }}>Pasien</option>
                    <option value="dokter" {{ request('role')=='dokter'?'selected':'' }}>Dokter</option>
                    <option value="perawat" {{ request('role')=='perawat'?'selected':'' }}>Perawat</option>
                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                </select>
            </div>
            
            {{-- Tombol Tampilkan & Reset --}}
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                {{-- Label transparan agar tombol sejajar dengan input di sebelahnya --}}
                <label class="form-label" style="font-size: 0.875rem; visibility: hidden;">Action</label>
                <div style="display: flex; gap: 0.5rem; width: 100%;">
                    {{-- Tombol Tampilkan (Lebih lebar) --}}
                    <button type="submit" class="btn btn-primary" style="height: 40px; flex: 2; display: flex; justify-content: center; align-items: center; border-radius: 0.375rem; background-color: #2563eb; color: white; border: none; font-weight: 600; cursor: pointer; transition: background-color 0.2s; box-sizing: border-box;">
                        Tampilkan
                    </button>
                    {{-- Tombol Reset --}}
                    <a href="{{ route('admin.users.index') }}" style="height: 40px; flex: 1; display: flex; justify-content: center; align-items: center; border-radius: 0.375rem; background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; text-decoration: none; cursor: pointer; transition: background-color 0.2s; box-sizing: border-box;" title="Reset Filter">
                        Reset
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="card">
    @if($users->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada pengguna ditemukan</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Pengguna</th><th>Role</th><th>No. HP</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <img src="{{ $u->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:600; color:#1e293b;">{{ $u->name }}</div>
                                <div style="font-size:0.75rem;color:#64748b;">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleColors = [
                                'admin' => 'background:#fef3c7; color:#92400e;',
                                'dokter' => 'background:#dbeafe; color:#1e40af;',
                                'perawat' => 'background:#d1fae5; color:#065f46;',
                                'pasien' => 'background:#f1f5f9; color:#475569;'
                            ];
                        @endphp
                        <span class="badge" style="text-transform:capitalize; {{ $roleColors[$u->role] ?? '' }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td><span style="color:#64748b; font-size:0.875rem;">{{ $u->phone ?: '-' }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.toggle', $u->id) }}" x-ref="toggleForm{{ $u->id }}">
                            @csrf @method('PATCH')
                            <button type="button" 
                                @click="triggerConfirm(
                                    '{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}',
                                    '{{ $u->is_active ? 'Nonaktifkan akun '.$u->name.'? User ini tidak akan bisa login ke sistem.' : 'Aktifkan kembali akun '.$u->name.'?' }}',
                                    () => { $refs.toggleForm{{ $l->id ?? $u->id }}.submit() },
                                    '{{ $u->is_active ? 'danger' : 'primary' }}'
                                )"
                                class="badge {{ $u->is_active ? 'badge-success' : 'badge-danger' }}" 
                                style="border:none;cursor:pointer;" 
                                {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" x-ref="deleteForm{{ $u->id }}">
                                @csrf @method('DELETE')
                                <button type="button" 
                                    @click="triggerConfirm(
                                        'Hapus Permanen User',
                                        'Hapus permanen akun {{ $u->name }}? Tindakan ini TIDAK DAPAT dibatalkan.',
                                        () => { $refs.deleteForm{{ $u->id }}.submit() },
                                        'danger'
                                    )"
                                    class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $users->links() }}</div>
    @endif
</div>
<style>
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    @media(max-width: 768px) {
        .filter-grid { 
            grid-template-columns: 1fr 1fr; 
        }
    }
    
    @media(max-width: 640px) {
        .filter-grid { 
            grid-template-columns: 1fr; 
        }
    }
</style>
@endsection
