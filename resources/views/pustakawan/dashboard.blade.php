<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <title>Dashboard Pustakawan - ITH </title>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-700">
    <div class="flex">

        @include('layouts.partials.sidebar-pustakawan')

        <main class="flex-1 p-8 lg:p-12 h-screen overflow-y-auto">

            @if (session('success'))
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-xl mb-8 shadow-sm flex items-center justify-between">
                    <span class="font-bold">✨ {{ session('success') }}</span>
                    <button class="text-blue-500" onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Statistik <span class="text-blue-600">Sistem</span></h1>
                    <p class="text-slate-500 text-sm italic">Dashboard Pustakawan ITH</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('shared.peminjaman.create') }}"
                        class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold text-xs uppercase hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center gap-2">
                        <i class="bi bi-plus-circle-fill text-sm"></i>
                        Input Pinjaman Baru
                    </a>
                </div>
            </div>

            {{-- Statistik Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                {{-- Card Total Pengguna --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Pengguna</div>
                        <div class="text-3xl font-black text-slate-800">{{ $totalPengguna }}</div>
                    </div>
                </div>

                {{-- Card Total Koleksi --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div>
                        <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Koleksi Buku</div>
                        <div class="text-3xl font-black text-slate-800">{{ $totalKoleksi }}</div>
                    </div>
                </div>

                {{-- Card Pinjaman Aktif --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 text-2xl">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Pinjaman Aktif</div>
                        <div class="text-3xl font-black text-slate-800">{{ $pinjamanAktif }}</div>
                    </div>
                </div>
            </div>

            {{-- Monitoring Table --}}
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Monitoring Transaksi Terbaru</h3>
                    <a href="{{ route('shared.transaksi.index') }}" class="text-blue-600 text-[10px] font-bold uppercase hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="p-6 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">No</th>
                                <th class="p-6 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">Mahasiswa / Peminjam</th>
                                <th class="p-6 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">Judul Buku</th>
                                <th class="p-6 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">Tanggal Pinjam</th>
                                <th class="p-6 text-center text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($peminjamanTerbaru as $index => $p)
                                <tr class="hover:bg-slate-50/50 transition group">
                                    <td class="p-6 text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="p-6">
                                        <div class="font-bold text-slate-800 text-sm">{{ $p->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase font-mono">{{ $p->user->nim ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-6">
                                        <div class="text-sm text-slate-600 font-medium line-clamp-1">{{ $p->buku->judul }}</div>
                                    </td>
                                    <td class="p-6 text-sm text-slate-500 font-medium">
                                        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="p-6 text-center">
                                        <span class="px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider">
                                            {{ $p->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-20 text-center text-slate-400 italic">
                                        <i class="bi bi-inbox text-4xl block mb-4 opacity-20"></i>
                                        Tidak ada transaksi aktif hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>