<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
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
        .success-card {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .icon-wrapper {
            background: #d1fae5;
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon {
            color: #10b981;
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
        }
        .invoice-box {
            background: #f1f5f9;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .invoice-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .invoice-number {
            font-family: monospace;
            font-size: 1.25rem;
            font-weight: 800;
            color: #2563eb;
        }
        .amount {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1.5rem;
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
    <div class="success-card">
        <div class="icon-wrapper">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="title">Pembayaran Berhasil!</h1>
        <p class="subtitle">Terima kasih, tagihan Anda telah lunas dibayar melalui QRIS.</p>
        
        <div class="amount">{{ $pembayaran->jumlah_format }}</div>

        <div class="invoice-box">
            <div class="invoice-label">Invoice</div>
            <div class="invoice-number">{{ $pembayaran->kode_invoice }}</div>
        </div>

        <p style="font-size: 0.875rem; color: #475569; margin-bottom: 1.5rem;">
            Silakan kembali ke aplikasi di perangkat awal Anda atau tutup halaman ini.
        </p>

        <a href="{{ route('home') }}" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
