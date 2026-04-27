<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased" x-data="{ search: '' }">

    <div class="flex min-h-screen">
         @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                        Riwayat <span class="text-blue-600">Peminjaman</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-2 font-semibold italic">Log sirkulasi buku yang telah dikembalikan ke Perpustakaan ITH</p>
                </div>

                <button class="flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-3 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-sm active:scale-95">
                    <i class="bi bi-printer text-sm"></i>
                    Cetak Laporan
                </button>
            </header>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <div class="relative w-full md:w-96">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                            <i class="bi bi-search text-xs"></i>
                        </span>
                        <input type="text" x-model="search"
                            class="w-full bg-white border border-slate-200 rounded-2xl py-3.5 pl-12 pr-4 text-[11px] font-bold text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                            placeholder="Cari Peminjam, Judul, atau Kode Buku...">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Peminjam</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Detail Buku & Kode</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Klasifikasi</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center tracking-widest">Periode Pinjam</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center tracking-widest">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-[11px]">
                            @forelse($riwayat as $r)
                            <tr class="hover:bg-slate-50/50 transition duration-200"
                                x-show="search === '' ||
                                        '{{ strtolower($r->user->name ?? '') }}'.includes(search.toLowerCase()) ||
                                        '{{ strtolower($r->buku->judul ?? '') }}'.includes(search.toLowerCase())">

                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 font-black text-[10px]">
                                            {{ substr($r->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 uppercase tracking-tighter">{{ $r->user->name ?? 'User Hilang' }}</p>
                                            <p class="text-[9px] text-blue-500 font-bold uppercase italic">{{ $r->user->nomor_identitas ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <p class="font-black text-slate-700 uppercase leading-tight">{{ $r->buku->judul ?? 'Buku Dihapus' }}</p>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-[9px] font-black text-white bg-slate-800 px-2 py-0.5 rounded italic">
                                            {{ $r->buku->kode_buku ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-center">
                                    <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-100 italic">
                                        {{ $r->buku->kategori->nama_kategori ?? 'UMUM' }}
                                    </span>
                                </td>

                                <td class="px-8 py-6 text-center">
                                    <div class="flex flex-col items-center justify-center gap-1">
                                        <span class="font-bold text-slate-600">
                                            {{ $r->tgl_pinjam ? \Carbon\Carbon::parse($r->tgl_pinjam)->format('d/m/Y') : '-' }}
                                        </span>
                                        <div class="h-3 w-px bg-slate-200"></div>
                                        <span class="font-bold text-emerald-600">
                                            {{ $r->tgl_kembali ? \Carbon\Carbon::parse($r->tgl_kembali)->format('d/m/Y') : '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-center">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full font-black uppercase text-[9px] border border-emerald-100">
                                        {{ strtoupper($r->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center italic text-slate-400">
                                        <i class="bi bi-clock-history text-4xl mb-2"></i>
                                        <p class="font-bold uppercase text-[10px]">Belum ada riwayat peminjaman yang selesai</p>
                                    </div>
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
