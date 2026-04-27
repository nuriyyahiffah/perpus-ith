<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased">

    {{-- HEADER --}}
    <div class="bg-[#2D3E50] text-white py-6">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-5">
                    {{-- Perbaikan: Sesuaikan route dashboard kamu (admin.dashboard atau dashboard) --}}
                    <a href="{{ route('admin.dashboard') }}" class="bg-white/10 hover:bg-white/20 h-10 w-10 rounded-xl flex items-center justify-center transition">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                    <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-14 w-auto">
                    <div class="border-l border-white/20 pl-5">
                        <h1 class="text-2xl font-black uppercase tracking-tight">Laporan Bulanan</h1>
                        <p class="text-slate-300 text-sm font-medium">Periode: {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    {{-- Perbaikan: Route disamakan dengan web.php --}}
                    <a href="{{ route('shared.laporan.export_pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
                       class="bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 rounded-xl text-sm font-bold uppercase transition shadow-lg flex items-center">
                        <i class="bi bi-file-earmark-pdf me-2"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main class="container mx-auto px-6 py-10">
        <div class="max-w-7xl mx-auto">

            {{-- FILTER PERIODE --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
                <form method="GET" action="{{ route('shared.laporan.bulanan') }}" class="flex flex-wrap items-center gap-4">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Pilih Periode:</label>
                    <select name="bulan" class="px-4 py-2 border border-slate-200 rounded-xl outline-none text-sm font-bold text-slate-700 bg-slate-50">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02d', $i) }}" {{ $i == $bulan ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" class="px-4 py-2 border border-slate-200 rounded-xl outline-none text-sm font-bold text-slate-700 bg-slate-50">
                        @for($i = date('Y'); $i >= 2023; $i--)
                            <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2 rounded-xl font-bold text-sm uppercase transition">
                        <i class="bi bi-search me-2"></i> Tampilkan
                    </button>
                </form>
            </div>

            {{-- STATISTIK RINGKASAN --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                    <div class="text-2xl font-black text-slate-800">{{ $statistikBulanan['total_peminjaman'] }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Peminjaman</div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                    <div class="text-2xl font-black text-slate-800">{{ $statistikBulanan['total_pengembalian'] }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pengembalian</div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border p-6 text-center text-rose-600">
                    <div class="text-2xl font-black">{{ $statistikBulanan['total_terlambat'] }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Terlambat</div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                    <div class="text-2xl font-black text-emerald-600">Rp{{ number_format($statistikBulanan['total_denda'], 0, ',', '.') }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Denda</div>
                </div>
            </div>

            {{-- CHART TREN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 uppercase tracking-tight">Tren Aktivitas Harian</h3>
                <div style="height: 350px;">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
            
            {{-- DATA TABEL TRANSAKSI (Sesuai Urutan PDF) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Detail Transaksi Bulan Ini</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Tgl Pinjam</th>
                                <th class="px-6 py-4">Nama Anggota</th>
                                <th class="px-6 py-4">Judul Buku</th>
                                <th class="px-6 py-4 text-center">No. Induk</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-600">
    @forelse($dataTransaksi as $index => $item)
    <tr class="hover:bg-slate-50/50 transition">
        <td class="px-6 py-4">{{ $index + 1 }}</td>
        
        {{-- Tanggal Pinjam --}}
        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
        
        {{-- Nama Anggota --}}
        <td class="px-6 py-4 text-slate-900 font-bold">{{ $item->user->name }}</td>
        
        {{-- Judul Buku (Diambil dari relasi eksemplar ke buku) --}}
        <td class="px-6 py-4">{{ $item->eksemplar->buku->judul ?? '-' }}</td>
        
        {{-- No. Induk (Diambil langsung dari tabel eksemplar) --}}
        <td class="px-6 py-4 text-center">
            <span class="bg-slate-100 px-2 py-1 rounded text-xs font-mono font-bold text-slate-700">
                {{ $item->eksemplar->no_induk ?? '-' }}
            </span>
        </td>
        
        {{-- Status --}}
        <td class="px-6 py-4 text-center">
            @if($item->status == 'dipinjam')
                <span class="text-orange-600 bg-orange-50 px-3 py-1 rounded-full text-xs font-bold uppercase">Dipinjam</span>
            @else
                <span class="text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full text-xs font-bold uppercase">Kembali</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-10 text-center text-slate-400 font-bold italic">Tidak ada data transaksi ditemukan.</td>
    </tr>
    @endforelse
</tbody>

                    </table>
                </div>
            </div>

            {{-- FOOTER INFO --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Buku Terpopuler --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Buku Terpopuler</h3>
                    <div class="space-y-4">
                        @foreach($statistikBulanan['buku_terpopuler'] as $buku)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <span class="text-sm font-bold text-slate-700 truncate w-2/3">{{ $loop->iteration }}. {{ $buku->judul }}</span>
                            <span class="text-xs font-black bg-blue-600 text-white px-3 py-1 rounded-lg">{{ $buku->total_pinjam }}x Pinjam</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Anggota Aktif --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Anggota Teraktif</h3>
                    <div class="space-y-4">
                        @foreach($statistikBulanan['anggota_aktif'] as $user)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <span class="text-sm font-bold text-slate-700 truncate w-2/3">{{ $loop->iteration }}. {{ $user->name }}</span>
                            <span class="text-xs font-black bg-emerald-600 text-white px-3 py-1 rounded-lg">{{ $user->total_pinjam }}x Pinjam</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
            const trenData = @json($trenHarian);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trenData.map(item => item.tanggal),
                    datasets: [
                        {
                            label: 'Peminjaman',
                            data: trenData.map(item => item.peminjaman),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Pengembalian',
                            data: trenData.map(item => item.pengembalian),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { font: { weight: 'bold' } } } },
                    scales: { 
                        y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: '#f1f5f9' } },
                        x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                    }
                }
            });
        });
    </script>
</body>
</html>