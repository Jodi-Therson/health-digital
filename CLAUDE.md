# AGENTS.md — Skema Arsitektur: Platform Kesehatan Digital

> Dokumen ini digunakan oleh Gemini Coding Agent sebagai panduan pembangunan sistem.
> Setiap agent harus membaca seluruh dokumen ini sebelum mulai menulis kode.

---

## 1. IDENTITAS PROYEK

| Atribut | Detail |
|---|---|
| **Nama Proyek** | HealthDigital — Platform Layanan Kesehatan Berbasis Web |
| **Deskripsi** | Portal digital terintegrasi yang menghubungkan pasien, tenaga medis, dan admin rumah sakit melalui pendaftaran online, rekam medis digital, konsultasi, pembayaran, dan informasi layanan |
| **Framework** | Laravel 12 |
| **CSS Framework** | Tailwind CSS 4 |
| **Database** | MySQL 8.0.30 |
| **PHP Version** | PHP 8.3+ |
| **Node.js** | v20+ (untuk asset bundling Vite) |
| **Tema** | Putih & Biru — clean, minimalis, medis |
| **Standar UX** | Jakob Nielsen's 10 Usability Heuristics |

---

## 2. PRINSIP DESAIN — JAKOB NIELSEN'S 10 USABILITY HEURISTICS

Setiap halaman dan komponen HARUS memenuhi prinsip berikut:

| # | Heuristik | Implementasi Wajib |
|---|---|---|
| 1 | **Visibility of System Status** | Loading spinner, progress bar antrian, status badge rekam medis, toast notification setiap aksi |
| 2 | **Match Between System & Real World** | Gunakan bahasa Indonesia medis yang familiar; ikon dokter, kalender, resep, dll. |
| 3 | **User Control & Freedom** | Tombol "Batal" dan "Kembali" di setiap form; konfirmasi sebelum hapus |
| 4 | **Consistency & Standards** | Design system seragam: warna, tipografi, komponen Blade identik di semua halaman |
| 5 | **Error Prevention** | Validasi real-time (Alpine.js), konfirmasi modal sebelum submit pembayaran/hapus data |
| 6 | **Recognition Rather Than Recall** | Breadcrumb, sidebar aktif highlight, riwayat kunjungan terlihat di dashboard |
| 7 | **Flexibility & Efficiency** | Shortcut navigasi untuk dokter, filter/search cepat di rekam medis |
| 8 | **Aesthetic & Minimalist Design** | Layout putih-biru, hanya tampilkan informasi relevan per role |
| 9 | **Help Users Recognize, Diagnose & Recover From Errors** | Pesan error spesifik ("Nomor BPJS tidak valid"), bukan "Error 422" |
| 10 | **Help & Documentation** | Halaman FAQ, tooltip inline, panduan penggunaan per role |

---

## 3. STRUKTUR ROLE & PERMISSION

### 3.1 Role Definitions

```
roles:
  - pasien       → pengguna umum yang mendaftar sendiri
  - dokter       → tenaga medis yang menangani pasien
  - perawat      → asisten medis, akses terbatas rekam medis
  - admin        → superuser pengelola sistem rumah sakit
```

### 3.2 Permission Matrix

| Fitur | Pasien | Perawat | Dokter | Admin |
|---|:---:|:---:|:---:|:---:|
| Daftar akun & login | ✅ | ✅ | ✅ | ✅ |
| Pendaftaran antrian online | ✅ | ✅ | — | ✅ |
| Lihat antrian sendiri | ✅ | ✅ | ✅ | ✅ |
| Kelola antrian semua pasien | — | ✅ | — | ✅ |
| Lihat rekam medis sendiri | ✅ | — | — | — |
| Buat/edit rekam medis | — | ✅ | ✅ | — |
| Lihat rekam medis pasien | — | ✅ | ✅ | ✅ |
| Konsultasi online (buat) | ✅ | — | — | — |
| Konsultasi online (jawab) | — | — | ✅ | — |
| Pembayaran digital | ✅ | — | — | ✅ |
| Verifikasi pembayaran | — | — | — | ✅ |
| Kelola dokter & perawat | — | — | — | ✅ |
| Kelola layanan & fasilitas | — | — | — | ✅ |
| Laporan & statistik | — | — | — | ✅ |

---

## 4. ARSITEKTUR DIREKTORI LARAVEL

```
healthdigital/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── Pasien/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── AntrianController.php
│   │   │   │   ├── RekamMedisController.php
│   │   │   │   ├── KonsultasiController.php
│   │   │   │   └── PembayaranController.php
│   │   │   ├── Dokter/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── AntrianController.php
│   │   │   │   ├── RekamMedisController.php
│   │   │   │   └── KonsultasiController.php
│   │   │   ├── Perawat/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── AntrianController.php
│   │   │   │   └── RekamMedisController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── LayananController.php
│   │   │   │   ├── PembayaranController.php
│   │   │   │   └── LaporanController.php
│   │   │   └── PublicController.php
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   └── CheckProfileComplete.php
│   │   └── Requests/
│   │       ├── AntrianRequest.php
│   │       ├── RekamMedisRequest.php
│   │       ├── KonsultasiRequest.php
│   │       └── PembayaranRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Pasien.php
│   │   ├── Dokter.php
│   │   ├── Perawat.php
│   │   ├── Antrian.php
│   │   ├── RekamMedis.php
│   │   ├── Konsultasi.php
│   │   ├── Pembayaran.php
│   │   ├── Layanan.php
│   │   └── Fasilitas.php
│   ├── Services/
│   │   ├── AntrianService.php
│   │   ├── RekamMedisService.php
│   │   ├── KonsultasiService.php
│   │   └── PembayaranService.php
│   └── Policies/
│       ├── RekamMedisPolicy.php
│       └── KonsultasiPolicy.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_pasiens_table.php
│   │   ├── 2024_01_01_000003_create_dokters_table.php
│   │   ├── 2024_01_01_000004_create_perawats_table.php
│   │   ├── 2024_01_01_000005_create_layanans_table.php
│   │   ├── 2024_01_01_000006_create_fasilitas_table.php
│   │   ├── 2024_01_01_000007_create_antrians_table.php
│   │   ├── 2024_01_01_000008_create_rekam_medis_table.php
│   │   ├── 2024_01_01_000009_create_konsultasis_table.php
│   │   └── 2024_01_01_000010_create_pembayarans_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       ├── LayananSeeder.php
│       └── FasilitasSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php          ← layout utama (navbar + sidebar + footer)
│   │   │   ├── auth.blade.php         ← layout halaman autentikasi
│   │   │   └── public.blade.php       ← layout halaman publik
│   │   ├── components/
│   │   │   ├── navbar.blade.php
│   │   │   ├── sidebar.blade.php
│   │   │   ├── footer.blade.php
│   │   │   ├── alert.blade.php
│   │   │   ├── modal.blade.php
│   │   │   ├── badge-status.blade.php
│   │   │   └── loading-spinner.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   └── forgot-password.blade.php
│   │   ├── public/
│   │   │   ├── home.blade.php
│   │   │   ├── layanan.blade.php
│   │   │   ├── fasilitas.blade.php
│   │   │   └── kontak.blade.php
│   │   ├── pasien/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── antrian/
│   │   │   ├── rekam-medis/
│   │   │   ├── konsultasi/
│   │   │   └── pembayaran/
│   │   ├── dokter/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── antrian/
│   │   │   ├── rekam-medis/
│   │   │   └── konsultasi/
│   │   ├── perawat/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── antrian/
│   │   │   └── rekam-medis/
│   │   └── admin/
│   │       ├── dashboard.blade.php
│   │       ├── users/
│   │       ├── layanan/
│   │       ├── pembayaran/
│   │       └── laporan/
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── api.php
├── config/
│   └── healthdigital.php
└── tests/
    ├── Feature/
    └── Unit/
```

---

## 5. SKEMA DATABASE (MySQL 8.0.30)

### 5.1 Tabel `users`
```sql
CREATE TABLE users (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(255) NOT NULL,
    email           VARCHAR(255) UNIQUE NOT NULL,
    password        VARCHAR(255) NOT NULL,
    role            ENUM('pasien','dokter','perawat','admin') NOT NULL DEFAULT 'pasien',
    phone           VARCHAR(20),
    avatar          VARCHAR(255),
    is_active       BOOLEAN DEFAULT TRUE,
    email_verified_at TIMESTAMP NULL,
    remember_token  VARCHAR(100),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 5.2 Tabel `pasiens`
```sql
CREATE TABLE pasiens (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL,
    nik             VARCHAR(16) UNIQUE NOT NULL,
    no_bpjs         VARCHAR(20) UNIQUE,
    tanggal_lahir   DATE NOT NULL,
    jenis_kelamin   ENUM('L','P') NOT NULL,
    golongan_darah  ENUM('A','B','AB','O') NULL,
    alamat          TEXT,
    kota            VARCHAR(100),
    pekerjaan       VARCHAR(100),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 5.3 Tabel `dokters`
```sql
CREATE TABLE dokters (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL,
    no_str          VARCHAR(50) UNIQUE NOT NULL,
    spesialisasi    VARCHAR(100) NOT NULL,
    jadwal          JSON,           -- {"senin":["08:00","12:00"], ...}
    tarif_konsultasi DECIMAL(10,2) DEFAULT 0,
    bio             TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 5.4 Tabel `perawats`
```sql
CREATE TABLE perawats (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL,
    no_str          VARCHAR(50) UNIQUE NOT NULL,
    bagian          VARCHAR(100),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 5.5 Tabel `layanans`
```sql
CREATE TABLE layanans (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama            VARCHAR(255) NOT NULL,
    deskripsi       TEXT,
    ikon            VARCHAR(100),
    is_active       BOOLEAN DEFAULT TRUE,
    urutan          INT DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 5.6 Tabel `fasilitas`
```sql
CREATE TABLE fasilitas (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama            VARCHAR(255) NOT NULL,
    deskripsi       TEXT,
    foto            VARCHAR(255),
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 5.7 Tabel `antrians`
```sql
CREATE TABLE antrians (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pasien_id       BIGINT UNSIGNED NOT NULL,
    dokter_id       BIGINT UNSIGNED NOT NULL,
    layanan_id      BIGINT UNSIGNED NOT NULL,
    tanggal         DATE NOT NULL,
    no_antrian      VARCHAR(10) NOT NULL,
    keluhan         TEXT,
    status          ENUM('menunggu','dipanggil','selesai','batal') DEFAULT 'menunggu',
    catatan_perawat TEXT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pasien_id)  REFERENCES pasiens(id),
    FOREIGN KEY (dokter_id)  REFERENCES dokters(id),
    FOREIGN KEY (layanan_id) REFERENCES layanans(id)
);
```

### 5.8 Tabel `rekam_medis`
```sql
CREATE TABLE rekam_medis (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    antrian_id      BIGINT UNSIGNED NOT NULL,
    pasien_id       BIGINT UNSIGNED NOT NULL,
    dokter_id       BIGINT UNSIGNED NOT NULL,
    tanggal_periksa DATE NOT NULL,
    anamnesis       TEXT,
    diagnosa        TEXT NOT NULL,
    tindakan        TEXT,
    resep           JSON,           -- [{"obat":"...", "dosis":"...", "aturan":"..."}]
    tekanan_darah   VARCHAR(20),
    berat_badan     DECIMAL(5,2),
    tinggi_badan    DECIMAL(5,2),
    suhu_tubuh      DECIMAL(4,1),
    catatan         TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (antrian_id) REFERENCES antrians(id),
    FOREIGN KEY (pasien_id)  REFERENCES pasiens(id),
    FOREIGN KEY (dokter_id)  REFERENCES dokters(id)
);
```

### 5.9 Tabel `konsultasis`
```sql
CREATE TABLE konsultasis (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pasien_id       BIGINT UNSIGNED NOT NULL,
    dokter_id       BIGINT UNSIGNED NOT NULL,
    judul           VARCHAR(255) NOT NULL,
    pesan           TEXT NOT NULL,
    balasan         TEXT NULL,
    status          ENUM('menunggu','dijawab','ditutup') DEFAULT 'menunggu',
    dibaca_dokter   BOOLEAN DEFAULT FALSE,
    dibaca_pasien   BOOLEAN DEFAULT FALSE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pasien_id) REFERENCES pasiens(id),
    FOREIGN KEY (dokter_id) REFERENCES dokters(id)
);
```

### 5.10 Tabel `pembayarans`
```sql
CREATE TABLE pembayarans (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    antrian_id      BIGINT UNSIGNED NOT NULL,
    pasien_id       BIGINT UNSIGNED NOT NULL,
    kode_invoice    VARCHAR(50) UNIQUE NOT NULL,
    jumlah          DECIMAL(12,2) NOT NULL,
    metode          ENUM('bpjs','transfer','tunai','qris') NOT NULL,
    status          ENUM('menunggu','dibayar','gagal','dikembalikan') DEFAULT 'menunggu',
    bukti_bayar     VARCHAR(255) NULL,
    catatan         TEXT NULL,
    dibayar_pada    TIMESTAMP NULL,
    verified_by     BIGINT UNSIGNED NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (antrian_id) REFERENCES antrians(id),
    FOREIGN KEY (pasien_id)  REFERENCES pasiens(id),
    FOREIGN KEY (verified_by) REFERENCES users(id)
);
```

---

## 6. ROUTING STRUKTUR

### `routes/web.php` — Struktur Lengkap

```php
<?php
// ── PUBLIC ROUTES ──────────────────────────────────────────
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/fasilitas', [PublicController::class, 'fasilitas'])->name('fasilitas');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');

// ── AUTH ROUTES ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── PASIEN ROUTES ──────────────────────────────────────────
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [Pasien\DashboardController::class, 'index'])->name('dashboard');
    // Antrian
    Route::resource('antrian', Pasien\AntrianController::class)->only(['index','create','store','show']);
    // Rekam Medis
    Route::get('rekam-medis', [Pasien\RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('rekam-medis/{id}', [Pasien\RekamMedisController::class, 'show'])->name('rekam-medis.show');
    // Konsultasi
    Route::resource('konsultasi', Pasien\KonsultasiController::class)->only(['index','create','store','show']);
    // Pembayaran
    Route::resource('pembayaran', Pasien\PembayaranController::class)->only(['index','show','update']);
    Route::post('pembayaran/{id}/upload', [Pasien\PembayaranController::class, 'uploadBukti'])->name('pembayaran.upload');
});

// ── DOKTER ROUTES ──────────────────────────────────────────
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [Dokter\DashboardController::class, 'index'])->name('dashboard');
    // Antrian
    Route::get('antrian', [Dokter\AntrianController::class, 'index'])->name('antrian.index');
    Route::patch('antrian/{id}/status', [Dokter\AntrianController::class, 'updateStatus'])->name('antrian.status');
    // Rekam Medis
    Route::resource('rekam-medis', Dokter\RekamMedisController::class)->only(['index','create','store','show','edit','update']);
    // Konsultasi
    Route::resource('konsultasi', Dokter\KonsultasiController::class)->only(['index','show','update']);
});

// ── PERAWAT ROUTES ─────────────────────────────────────────
Route::middleware(['auth', 'role:perawat'])->prefix('perawat')->name('perawat.')->group(function () {
    Route::get('/dashboard', [Perawat\DashboardController::class, 'index'])->name('dashboard');
    // Antrian
    Route::resource('antrian', Perawat\AntrianController::class)->only(['index','show','create','store']);
    Route::patch('antrian/{id}/panggil', [Perawat\AntrianController::class, 'panggil'])->name('antrian.panggil');
    // Rekam Medis (read + tambah catatan perawat)
    Route::get('rekam-medis', [Perawat\RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('rekam-medis/{id}', [Perawat\RekamMedisController::class, 'show'])->name('rekam-medis.show');
    Route::patch('rekam-medis/{id}/catatan', [Perawat\RekamMedisController::class, 'tambahCatatan'])->name('rekam-medis.catatan');
});

// ── ADMIN ROUTES ───────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    // User Management
    Route::resource('users', Admin\UserController::class);
    Route::patch('users/{id}/toggle-aktif', [Admin\UserController::class, 'toggleAktif'])->name('users.toggle');
    // Layanan & Fasilitas
    Route::resource('layanan', Admin\LayananController::class);
    Route::resource('fasilitas', Admin\FasilitasController::class);
    // Pembayaran
    Route::get('pembayaran', [Admin\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::patch('pembayaran/{id}/verifikasi', [Admin\PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    // Laporan
    Route::get('laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [Admin\LaporanController::class, 'export'])->name('laporan.export');
});
```

---

## 7. DESIGN SYSTEM — TEMA PUTIH & BIRU

### 7.1 Palet Warna (Tailwind CSS 4 custom tokens)
```css
/* resources/css/app.css */
@import "tailwindcss";

@theme {
  /* Primary Blue */
  --color-primary-50:  #eff6ff;
  --color-primary-100: #dbeafe;
  --color-primary-200: #bfdbfe;
  --color-primary-300: #93c5fd;
  --color-primary-400: #60a5fa;
  --color-primary-500: #3b82f6;
  --color-primary-600: #2563eb;
  --color-primary-700: #1d4ed8;
  --color-primary-800: #1e40af;
  --color-primary-900: #1e3a8a;

  /* Neutral */
  --color-neutral-50:  #f8fafc;
  --color-neutral-100: #f1f5f9;
  --color-neutral-200: #e2e8f0;
  --color-neutral-700: #334155;
  --color-neutral-900: #0f172a;

  /* Status */
  --color-success: #10b981;
  --color-warning: #f59e0b;
  --color-danger:  #ef4444;
  --color-info:    #06b6d4;

  /* Typography */
  --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;

  /* Border Radius */
  --radius-card: 0.75rem;
  --radius-btn:  0.5rem;
}
```

### 7.2 Komponen UI Wajib

```
Komponen Blade yang harus dibuat di resources/views/components/:

1. <x-btn>           → Primary (biru), Secondary (outline), Danger (merah)
2. <x-card>          → Kartu konten dengan shadow ringan
3. <x-badge-status>  → Badge warna untuk status antrian/pembayaran/konsultasi
4. <x-alert>         → Alert success/warning/error dengan ikon
5. <x-modal>         → Modal konfirmasi/form (Alpine.js)
6. <x-form-input>    → Input + label + error message terintegrasi
7. <x-stat-card>     → Kartu statistik untuk dashboard
8. <x-loading>       → Spinner/skeleton loading
9. <x-empty-state>   → Ilustrasi ketika data kosong
10. <x-breadcrumb>   → Navigasi hierarki halaman
```

### 7.3 Layout Utama (`layouts/app.blade.php`)
```
┌─────────────────────────────────────────────────────┐
│  NAVBAR  [Logo] [Menu] [Notifikasi] [User Avatar]   │  ← h-16, bg-white, shadow-sm
├──────────┬──────────────────────────────────────────┤
│          │                                          │
│ SIDEBAR  │         MAIN CONTENT                     │
│ (w-64)   │         (flex-1, bg-neutral-50)          │
│          │                                          │
│ [Ikon]   │  ┌─ Breadcrumb ──────────────────────┐  │
│ Menu     │  │                                   │  │
│ Item     │  │  Page Title                       │  │
│          │  │                                   │  │
│ [Active  │  │  Content Area                     │  │
│  bg-blue]│  │                                   │  │
│          │  └───────────────────────────────────┘  │
├──────────┴──────────────────────────────────────────┤
│  FOOTER  © 2025 HealthDigital. All rights reserved. │
└─────────────────────────────────────────────────────┘
```

---

## 8. FITUR UTAMA — SPESIFIKASI LENGKAP

### 8.1 Pendaftaran & Antrian Online

**Flow:**
```
Pasien login → Pilih Layanan → Pilih Dokter → Pilih Tanggal →
Sistem generate No. Antrian → Konfirmasi via notifikasi →
Perawat/Admin panggil antrian → Status update realtime
```

**Generate Nomor Antrian:**
```php
// Format: [Kode Layanan][Tanggal][Urutan]
// Contoh: UMU-20250610-001
// Logic di AntrianService::generateNomor($layanan_id, $tanggal)
```

**Status Antrian & Badge Warna:**
- `menunggu` → badge kuning
- `dipanggil` → badge biru berkedip
- `selesai` → badge hijau
- `batal` → badge merah

---

### 8.2 Rekam Medis Digital

**Akses Control:**
- Pasien: hanya rekam medis milik sendiri (read-only)
- Dokter: semua pasien yang pernah ditangani (read + write)
- Perawat: semua (read + tambah catatan perawat saja)
- Admin: semua (read-only)

**Struktur rekam medis satu kunjungan:**
```
[ Informasi Kunjungan ]
  - Tanggal, Dokter, Layanan, No. Antrian

[ Vital Signs (diisi perawat) ]
  - Tekanan darah, BB, TB, Suhu

[ Pemeriksaan Dokter ]
  - Anamnesis (keluhan detail)
  - Diagnosa (ICD-10 friendly)
  - Tindakan yang dilakukan

[ Resep ]
  - Tabel: Nama Obat | Dosis | Aturan Pakai

[ Catatan Tambahan ]
```

---

### 8.3 Konsultasi Online

**Flow:**
```
Pasien buat pertanyaan (judul + pesan + pilih dokter) →
Dokter terima notifikasi → Dokter tulis balasan →
Pasien terima notifikasi → Status: dijawab
```

**Aturan:**
- Satu konsultasi per pasien-dokter (tidak ada live chat, bersifat async)
- Dokter bisa menutup konsultasi setelah menjawab
- Pasien tidak bisa membuat konsultasi baru ke dokter yang sama jika masih ada yang `menunggu`

---

### 8.4 Pembayaran Digital

**Metode Pembayaran:**
- BPJS (otomatis, tidak perlu upload bukti)
- Transfer Bank (upload bukti, verifikasi admin)
- Tunai (dicatat admin)
- QRIS (upload bukti, verifikasi admin)

**Flow:**
```
Antrian selesai → Admin/sistem buat invoice →
Pasien lihat tagihan → Pasien bayar & upload bukti →
Admin verifikasi → Status: dibayar → Cetak kwitansi
```

**Format Kode Invoice:**
```
INV-{YYYYMMDD}-{ID padded 6 digit}
Contoh: INV-20250610-000042
```

---

### 8.5 Informasi Layanan & Fasilitas

**Halaman Publik (tidak perlu login):**
- `/layanan` → grid card semua layanan aktif
- `/fasilitas` → galeri fasilitas dengan foto
- `/kontak` → form kontak + peta lokasi (embed Google Maps)

---

## 9. MIDDLEWARE & GUARD

### 9.1 `RoleMiddleware.php`
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Akses ditolak. Role Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
```

### 9.2 Register Middleware di `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

### 9.3 Redirect Setelah Login (by Role)
```php
// AuthController::login()
$redirectMap = [
    'pasien'  => route('pasien.dashboard'),
    'dokter'  => route('dokter.dashboard'),
    'perawat' => route('perawat.dashboard'),
    'admin'   => route('admin.dashboard'),
];
return redirect($redirectMap[auth()->user()->role]);
```

---

## 10. NOTIFIKASI & FEEDBACK

### 10.1 Session Flash Messages
Gunakan di setiap controller setelah aksi berhasil/gagal:
```php
// Sukses
return redirect()->back()->with('success', 'Antrian berhasil dibuat!');

// Gagal
return redirect()->back()->with('error', 'Terjadi kesalahan. Coba lagi.');

// Info
return redirect()->back()->with('info', 'Anda sudah memiliki antrian untuk hari ini.');
```

### 10.2 Komponen Alert Blade
```blade
{{-- resources/views/components/alert.blade.php --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
        <x-heroicon-o-check-circle class="w-5 h-5" />
        {{ session('success') }}
    </div>
@endif
{{-- Repeat for error, info, warning --}}
```

---

## 11. VALIDASI FORM (Form Requests)

### `AntrianRequest.php`
```php
public function rules(): array
{
    return [
        'dokter_id'  => 'required|exists:dokters,id',
        'layanan_id' => 'required|exists:layanans,id',
        'tanggal'    => 'required|date|after_or_equal:today',
        'keluhan'    => 'required|string|min:10|max:500',
    ];
}

public function messages(): array
{
    return [
        'tanggal.after_or_equal' => 'Tanggal pendaftaran tidak boleh di masa lalu.',
        'keluhan.min'            => 'Keluhan minimal 10 karakter.',
    ];
}
```

---

## 12. SEEDER DATA AWAL

### `DatabaseSeeder.php` — Urutan Seeder
```php
$this->call([
    RoleSeeder::class,      // ← tidak ada tabel roles, cukup enum di users
    LayananSeeder::class,   // ← 6 layanan: Umum, Gigi, Anak, Kandungan, Bedah, Lab
    FasilitasSeeder::class, // ← 5 fasilitas: IGD, ICU, Rawat Inap, Farmasi, Radiologi
    UserSeeder::class,      // ← 1 admin, 3 dokter, 2 perawat, 5 pasien (demo)
]);
```

### Data Demo Default
```
ADMIN:
  email: admin@healthdigital.id | password: Admin@123

DOKTER:
  dr.budi@healthdigital.id   | password: Dokter@123 | Spesialis: Umum
  dr.siti@healthdigital.id   | password: Dokter@123 | Spesialis: Anak
  dr.andi@healthdigital.id   | password: Dokter@123 | Spesialis: Gigi

PERAWAT:
  perawat1@healthdigital.id  | password: Perawat@123
  perawat2@healthdigital.id  | password: Perawat@123

PASIEN:
  pasien1@gmail.com          | password: Pasien@123
```

---

## 13. DEPENDENCY & PACKAGE

### `composer.json` — Package Tambahan
```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^12.0",
        "laravel/sanctum": "^4.0",
        "spatie/laravel-activitylog": "^4.0",
        "barryvdh/laravel-dompdf": "^2.2",
        "intervention/image-laravel": "^1.0"
    },
    "require-dev": {
        "laravel/pint": "^1.0",
        "phpunit/phpunit": "^11.0"
    }
}
```

### `package.json` — Frontend
```json
{
    "devDependencies": {
        "@tailwindcss/vite": "^4.0",
        "alpinejs": "^3.14",
        "vite": "^6.0"
    }
}
```

---

## 14. INSTRUKSI UNTUK CODING AGENT

### Urutan Pembangunan (Step-by-Step)

```
FASE 1 — FONDASI (lakukan berurutan)
  Step 1: Setup project Laravel 12 + Tailwind CSS 4 + Vite
  Step 2: Buat semua migration & jalankan
  Step 3: Buat semua Model dengan relationship (belongsTo, hasMany, dll)
  Step 4: Jalankan Seeder
  Step 5: Buat RoleMiddleware & daftarkan
  Step 6: Buat AuthController (login, register, logout, redirect by role)
  Step 7: Buat layout Blade: app.blade.php, auth.blade.php, public.blade.php
  Step 8: Buat semua komponen Blade (x-btn, x-card, x-badge-status, dll)

FASE 2 — HALAMAN PUBLIK
  Step 9:  Halaman Home (hero section, layanan ringkas, CTA daftar)
  Step 10: Halaman Layanan & Fasilitas
  Step 11: Halaman Kontak

FASE 3 — PASIEN
  Step 12: Dashboard pasien (ringkasan antrian aktif, konsultasi pending)
  Step 13: Pendaftaran antrian (form wizard: layanan → dokter → jadwal → konfirmasi)
  Step 14: Riwayat antrian + detail
  Step 15: Rekam medis (list + detail read-only)
  Step 16: Konsultasi online (buat + lihat balasan)
  Step 17: Pembayaran (list tagihan + upload bukti)

FASE 4 — DOKTER
  Step 18: Dashboard dokter (pasien hari ini, konsultasi belum dijawab)
  Step 19: Kelola antrian (panggil, selesaikan)
  Step 20: Buat & edit rekam medis
  Step 21: Jawab konsultasi

FASE 5 — PERAWAT
  Step 22: Dashboard perawat
  Step 23: Kelola antrian (tambah & panggil)
  Step 24: Rekam medis (lihat + tambah catatan vital signs)

FASE 6 — ADMIN
  Step 25: Dashboard admin (statistik: total pasien, antrian hari ini, pendapatan)
  Step 26: Manajemen user (CRUD: dokter, perawat, pasien)
  Step 27: Manajemen layanan & fasilitas
  Step 28: Verifikasi pembayaran
  Step 29: Laporan & export PDF

FASE 7 — POLISH
  Step 30: Implementasi semua 10 Nielsen Heuristics (cek per halaman)
  Step 31: Responsive mobile design
  Step 32: Testing fitur utama
```

### Aturan Coding Wajib

```
1. SELALU gunakan Form Request untuk validasi (bukan validate() inline)
2. SELALU gunakan Service class untuk logika bisnis (bukan di Controller)
3. SELALU gunakan Blade Components (x-*) untuk elemen UI yang berulang
4. SELALU tambahkan loading state & error state di setiap halaman
5. SELALU gunakan route names (bukan hardcode URL)
6. SELALU tambahkan CSRF token di setiap form
7. SELALU sanitasi output dengan {{ }} bukan {!! !!} kecuali HTML yang aman
8. GUNAKAN Alpine.js untuk interaktivitas ringan (modal, dropdown, toggle)
9. JANGAN gunakan jQuery
10. SEMUA teks user-facing dalam Bahasa Indonesia
```

---

## 15. CHECKLIST HEURISTIC AUDIT

Sebelum setiap fase selesai, agent wajib memverifikasi:

```
☐ H1: Apakah ada feedback visual untuk setiap aksi user? (loading, success, error)
☐ H2: Apakah label dan teks menggunakan bahasa yang familiar untuk pasien/tenaga medis?
☐ H3: Apakah ada tombol "Batal" atau cara keluar di setiap form?
☐ H4: Apakah komponen, warna, dan ikon konsisten di semua halaman?
☐ H5: Apakah ada konfirmasi sebelum aksi destruktif (hapus, batalkan)?
☐ H6: Apakah sidebar dan breadcrumb selalu menunjukkan posisi user saat ini?
☐ H7: Apakah ada fitur search/filter di halaman dengan daftar panjang?
☐ H8: Apakah halaman bersih dari informasi yang tidak diperlukan?
☐ H9: Apakah pesan error spesifik dan membantu user memperbaiki kesalahan?
☐ H10: Apakah ada tooltip atau bantuan inline di field yang membingungkan?
```

---

*AGENTS.md — HealthDigital Platform v1.0*
*Dibuat untuk: Gemini Coding Agent*
*Terakhir diperbarui: 2025*