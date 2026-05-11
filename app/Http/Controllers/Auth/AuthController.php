<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $failures = session()->get('login_failures', 0) + 1;
            session()->put('login_failures', $failures);
            return back()->withErrors(['login_failed' => 'Email atau password yang Anda masukkan salah.'])->withInput($request->only('email'));
        }

        if (!$user->is_active) {
            return back()->withErrors(['login_failed' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'])->withInput($request->only('email'));
        }

        session()->forget('login_failures');
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $redirectMap = [
            'pasien'  => route('pasien.dashboard'),
            'dokter'  => route('dokter.dashboard'),
            'perawat' => route('perawat.dashboard'),
            'admin'   => route('admin.dashboard'),
        ];

        return redirect($redirectMap[$user->role]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'phone'         => 'nullable|string|max:20',
            'nik'           => 'required|string|size:16|unique:pasiens',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'nullable|string|max:500',
        ], [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'name.max'               => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar. Silakan masuk (login).',
            'password.required'      => 'Password wajib diisi.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.regex'         => 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, dan satu angka.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.size'               => 'NIK harus tepat 16 digit angka.',
            'nik.unique'             => 'NIK ini sudah pernah didaftarkan.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak boleh hari ini atau di masa depan.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'       => 'Pilihan jenis kelamin tidak valid.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pasien',
            'phone'    => $request->phone,
        ]);

        Pasien::create([
            'user_id'       => $user->id,
            'nik'           => $request->nik,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pasien.dashboard')->with('success', 'Selamat datang, ' . $user->name . '! Akun Anda berhasil dibuat.');
    }

    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('info', 'Jika email Anda terdaftar, link reset password akan dikirimkan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
