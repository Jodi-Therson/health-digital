@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Manajemen Pengguna</h1></div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah Pengguna</a>
</div>

<div class="card" style="margin-bottom:1rem;"><div class="card-body" style="padding:1rem;">
    <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label class="form-label" style="margin-bottom:0.25rem;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nama / Email..." style="width:250px;">
        </div>
        <div>
            <label class="form-label" style="margin-bottom:0.25rem;">Role</label>
            <select name="role" class="form-input" style="width:150px;">
                <option value="">Semua Role</option>
                <option value="pasien" {{ request('role')=='pasien'?'selected':'' }}>Pasien</option>
                <option value="dokter" {{ request('role')=='dokter'?'selected':'' }}>Dokter</option>
                <option value="perawat" {{ request('role')=='perawat'?'selected':'' }}>Perawat</option>
                <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
</div></div>

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
                            <img src="{{ $u->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;">
                            <div><div style="font-weight:600;">{{ $u->name }}</div><div style="font-size:0.75rem;color:#64748b;">{{ $u->email }}</div></div>
                        </div>
                    </td>
                    <td><span class="badge badge-neutral" style="text-transform:capitalize;">{{ $u->role }}</span></td>
                    <td>{{ $u->phone ?: '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.toggle', $u->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="badge {{ $u->is_active ? 'badge-success' : 'badge-danger' }}" style="border:none;cursor:pointer;" {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" onsubmit="return confirm('Hapus pengguna ini?');">
                                @csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button>
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
@endsection
