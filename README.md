# Health Digital

Situs website Kesehatan Digital yang berfungsi sebagai pintu utama layanan digital yang mengintegrasikan informasi medis, layanan administrasi, dan komunikasi antara pasien, tenaga medis, serta pengelola rumah sakit.

## Fitur

- Pendaftaran dan Antrian Online
- Rekam Medis Digital
- Konsultasi Online
- Pembayaran Digital
- Informasi Layanan dan Fasilitas

## Petunjuk Instalasi

1. Pertama clone repository ini dengan
```
git clone https://github.com/Jodi-Therson/health-digital.git
cd health-digital
```

2. Instal dependensi
```
composer install
npm install
```

3. Setup .env
```
cp .env.example .env
```

4. Ubah bagian database
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=health_digital
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan command ini
```
php artisan key:generate
```

6. Pastikan database sudah dibuat secara lokal
```
CREATE DATABASE health_digital;
```

7. Lalu jalankan perintah ini
```
php artisan migrate:fresh --seed
php artisan storage:link
```

8. Jika semua berhasil jalankan ini di cmd
```
php artisan serve
npm run dev
```

9. Email dan Password default
```
pasien@gmail.com:password
dokter@healthdigital.id:password
perawat@healthdigital.id:password
admin@healthdigital.id:admin123
```
