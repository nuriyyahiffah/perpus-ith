<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PERPUSTAKAAN ITH</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased flex h-screen overflow-hidden">

    @include('layouts.partials.sidebar-admin')

    <main class="flex-1 p-8 lg:p-12 overflow-y-auto custom-scrollbar">

        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter">Statistik <span class="text-blue-600">Sistem</span></h1>
                <p class="text-slate-500 text-sm mt-1">Pantau aktivitas perpustakaan secara real-time.</p>
            </div>
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-black uppercase text-slate-400 leading-none mb-1">Administrator</p>
                    <p class="text-xs font-bold text-slate-700">{{ Auth::user()->name ?? 'Admin ITH' }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-person-badge-fill text-xl"></i>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Total Pengguna</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $totalAnggota ?? 0 }}</h3>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <i class="bi bi-journal-bookmark-fill text-xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Total Koleksi</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $totalKoleksi ?? 0 }}</h3>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <i class="bi bi-clock-history text-xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Pinjaman Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $pinjamAktif ?? 0 }}</h3>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <i class="bi bi-person-x-fill text-xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Akun Tersuspend</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $akunTersuspend ?? 0 }}</h3>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-white">
                        <h2 class="font-black uppercase text-[11px] tracking-widest text-slate-600">Daftar Terlambat & Potensi Suspend</h2>
                        <span class="bg-red-50 text-red-600 text-[10px] font-bold px-3 py-1 rounded-full italic">Butuh Tindakan</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="p-6 text-[10px] font-black uppercase text-slate-400 px-8">Pengguna</th>
                                    <th class="p-6 text-[10px] font-black uppercase text-slate-400">Buku</th>
                                    <th class="p-6 text-[10px] font-black uppercase text-slate-400 text-center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($listTerlambat ?? [] as $row)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-6 px-8">
                                        <div class="font-bold text-sm text-slate-700">{{ $row->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">{{ $row->user->prodi ?? 'MAHASISWA' }}</div>
                                    </td>
                                    <td class="p-6">
                                        <div class="text-xs font-medium text-slate-600 truncate max-w-[150px]">{{ $row->buku->judul }}</div>
                                        <div class="text-[9px] text-red-500 font-black italic uppercase">Batas: {{ $row->tgl_kembali }}</div>
                                    </td>
                                    <td class="p-6 text-center">
                                        <button class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-red-100">
                                            Suspend
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-20 text-center text-slate-300 italic text-xs uppercase tracking-widest font-bold">
                                        Semua pengembalian tepat waktu ✨
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-amber-50 border border-amber-100 rounded-[2rem] p-6 relative overflow-hidden shadow-sm shadow-amber-100/50">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-200/30 rounded-full blur-2xl"></div>
                    <i class="bi bi-exclamation-triangle absolute -right-2 -bottom-2 text-7xl text-amber-200/40"></i>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center shadow-md shadow-amber-400/20">
                                <i class="bi bi-megaphone-fill text-white text-xs"></i>
                            </div>
                        
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="font-black uppercase text-[10px] tracking-widest text-slate-400 mb-6">Informasi Aturan ITH</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-xs text-slate-600 italic leading-relaxed">
                            <i class="bi bi-info-circle-fill text-blue-500 mt-0.5"></i>
                            <span>Akun tersuspend otomatis tidak bisa melakukan peminjaman.</span>
                        </li>
                        <li class="flex items-start gap-3 text-xs text-slate-600 italic leading-relaxed">
                            <i class="bi bi-check-circle-fill text-emerald-500 mt-0.5"></i>
                            <span>Status dibuka kembali setelah buku diganti/denda lunas.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
