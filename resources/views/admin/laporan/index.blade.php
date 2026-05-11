@extends('layouts.app')

@section('title', 'Laporan & Statistik')

@section('sidebar')
    @include('admin._sidebar')
@endsection

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h1 class="page-title" style="margin: 0; font-size: 1.5rem; font-weight: 700;">Laporan & Statistik</h1>
        <p class="page-subtitle" style="margin: 0; color: #64748b;">Analisis performa antrian dan pendapatan sistem</p>
    </div>
    <form action="{{ route('admin.laporan.export') }}" method="GET" target="_blank">
        <input type="hidden" name="bulan" value="{{ $bulan }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <input type="hidden" name="layanan_id" value="{{ $layanan_id }}">
        <input type="hidden" name="dokter_id" value="{{ $dokter_id }}">
        <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.375rem; background-color: #3b82f6; color: white; border: none; cursor: pointer; font-weight: 600;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export PDF
        </button>
    </form>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 1.5rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
    <div class="card-body" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="filter-grid">
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Bulan</label>
                <select name="bulan" class="form-input" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box;">
                    @php
                        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ $namaBulan[$m - 1] }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Tahun</label>
                <select name="tahun" class="form-input" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box;">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Layanan</label>
                <select name="layanan_id" class="form-input" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box;">
                    <option value="">Semua Layanan</option>
                    @foreach($layanans as $l)
                        <option value="{{ $l->id }}" {{ $layanan_id == $l->id ? 'selected' : '' }}>
                            {{ $l->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Dokter</label>
                <select name="dokter_id" class="form-input" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box;">
                    <option value="">Semua Dokter</option>
                    @foreach($dokters as $d)
                        <option value="{{ $d->id }}" {{ $dokter_id == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Tombol Tampilkan & Reset --}}
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                {{-- Label transparan agar tombol sejajar dengan dropdown di sebelahnya --}}
                <label class="form-label" style="font-size: 0.875rem; visibility: hidden;">Action</label>
                <div style="display: flex; gap: 0.5rem; width: 100%;">
                    {{-- Tombol Tampilkan (Lebih lebar) --}}
                    <button type="submit" class="btn btn-primary" style="height: 40px; flex: 2; display: flex; justify-content: center; align-items: center; border-radius: 0.375rem; background-color: #2563eb; color: white; border: none; font-weight: 600; cursor: pointer; transition: background-color 0.2s; box-sizing: border-box;">
                        Tampilkan
                    </button>
                    {{-- Tombol Reset --}}
                    <a href="{{ route('admin.laporan.index') }}" style="height: 40px; flex: 1; display: flex; justify-content: center; align-items: center; border-radius: 0.375rem; background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; text-decoration: none; cursor: pointer; transition: background-color 0.2s; box-sizing: border-box;" title="Reset Filter">
                        Reset
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

{{-- Stats Grid --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
    <div class="card" style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; border-left: 4px solid #3b82f6; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
        <div class="card-body" style="padding: 1.25rem;">
            <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 0.5rem;">Total Antrian</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ number_format($totalAntrian) }}</div>
        </div>
    </div>
    <div class="card" style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; border-left: 4px solid #10b981; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
        <div class="card-body" style="padding: 1.25rem;">
            <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 0.5rem;">Antrian Selesai</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ number_format($antriansSelesai) }}</div>
        </div>
    </div>
    <div class="card" style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; border-left: 4px solid #f59e0b; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
        <div class="card-body" style="padding: 1.25rem;">
            <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 0.5rem;">Total Pendapatan</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- Charts Grid --}}
<div class="chart-container" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="card" style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
        <div class="card-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #1e293b;">Trend Antrian Harian</div>
        <div class="card-body" style="padding: 1.25rem;">
            <div style="height: 300px;">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>
    <div class="card" style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
        <div class="card-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #1e293b;">Distribusi Layanan</div>
        <div class="card-body" style="padding: 1.25rem;">
            <div style="height: 300px;">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Table Pendapatan per Layanan --}}
<div class="card" style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
    <div class="card-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #1e293b;">
        Pendapatan per Layanan
    </div>
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; background-color: #f8fafc;">
                    <th style="padding: 1rem 1.25rem; color: #475569; font-weight: 600; font-size: 0.875rem;">Nama Layanan</th>
                    <th style="padding: 1rem 1.25rem; color: #475569; font-weight: 600; font-size: 0.875rem; text-align: center;">Jumlah Antrian</th>
                    <th style="padding: 1rem 1.25rem; color: #475569; font-weight: 600; font-size: 0.875rem; text-align: right;">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendapatanLayanan as $p)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 1rem 1.25rem; font-weight: 600; color: #1e293b;">
                            {{ $p['nama'] }}
                        </td>
                        <td style="padding: 1rem 1.25rem; text-align: center; color: #475569;">
                            {{ $p['count'] }}
                        </td>
                        <td style="padding: 1rem 1.25rem; text-align: right; font-weight: 700; color: #10b981;">
                            Rp {{ number_format($p['total'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="background: #f8fafc; font-weight: 700;">
                <tr>
                    <td style="padding: 1rem 1.25rem; color: #1e293b;">TOTAL</td>
                    <td style="padding: 1rem 1.25rem; text-align: center; color: #1e293b;">
                        {{ $totalAntrian }}
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: right; color: #1e293b;">
                        Rp {{ number_format($pendapatan, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<style>
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
    }
    
    @media(max-width: 1024px) {
        .filter-grid { 
            grid-template-columns: 1fr 1fr; 
        }
        .chart-container { 
            grid-template-columns: 1fr !important; 
        }
    }
    
    @media(max-width: 640px) {
        .filter-grid { 
            grid-template-columns: 1fr; 
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($antriansPerHariLabels) !!},
                datasets: [{
                    label: 'Antrian',
                    data: {!! json_encode($antriansPerHariData) !!},
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [2, 2] } 
                    },
                    x: { 
                        grid: { display: false } 
                    }
                }
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($distribusiLayanan->pluck('nama')) !!},
                datasets: [{
                    data: {!! json_encode($distribusiLayanan->pluck('antrians_count')) !!},
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { usePointStyle: true, padding: 20 } 
                    }
                }
            }
        });
    });
</script>
@endsection