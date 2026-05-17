<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Kedaluwarsa</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }
        .error-card {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .icon-wrapper {
            background: #fee2e2;
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon {
            color: #ef4444;
            width: 2.5rem;
            height: 2.5rem;
        }
        .title {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 0 0.5rem;
        }
        .subtitle {
            color: #64748b;
            font-size: 0.9375rem;
            margin: 0 0 1.5rem;
            line-height: 1.5;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 0.875rem;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: background 0.15s;
        }
        .btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="icon-wrapper">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="title">QR Code Tidak Valid</h1>
        <p class="subtitle">QR Code ini sudah kedaluwarsa atau tidak valid. Silakan memuat ulang halaman pembayaran untuk mendapatkan QR Code yang baru.</p>
        
        <a href="{{ route('home') }}" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
