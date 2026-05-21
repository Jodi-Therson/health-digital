<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Perawat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->role) $query->where('role', $request->role);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', Password::min(8)],
            'role'     => 'required|in:pasien,dokter,perawat,admin',
            'phone'    => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
            'avatar'   => $avatarPath,
        ]);

        // Buat profil berdasarkan role
        if ($request->role === 'dokter' && $request->no_str) {
            Dokter::create([
                'user_id'           => $user->id,
                'no_str'            => $request->no_str,
                'spesialisasi'      => $request->spesialisasi ?? 'Umum',
                'tarif_konsultasi'  => $request->tarif_konsultasi ?? 150000,
                'jadwal'            => $request->jadwal,
                'bio'               => $request->bio,
            ]);
        } elseif ($request->role === 'perawat' && $request->no_str_perawat) {
            Perawat::create([
                'user_id' => $user->id,
                'no_str'  => $request->no_str_perawat,
                'bagian'  => $request->bagian,
            ]);
        } elseif ($request->role === 'pasien' && $request->nik) {
            Pasien::create([
                'user_id'       => $user->id,
                'nik'           => $request->nik,
                'tanggal_lahir' => $request->tanggal_lahir ?? '1990-01-01',
                'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:pasien,dokter,perawat,admin',
            'phone' => 'nullable|string|max:20',
            'avatar'=> 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only('name', 'email', 'phone');
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        if ($user->role === 'dokter' && $user->dokter) {
            $user->dokter->update($request->only('no_str', 'spesialisasi', 'tarif_konsultasi', 'jadwal', 'bio'));
        } elseif ($user->role === 'perawat' && $user->perawat) {
            $user->perawat->update([
                'no_str' => $request->no_str,
                'bagian' => $request->bagian
            ]);
        } elseif ($user->role === 'pasien' && $user->pasien) {
            $user->pasien->update($request->only('nik', 'tanggal_lahir', 'jenis_kelamin', 'alamat'));
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleAktif($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun sendiri.');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }
}
