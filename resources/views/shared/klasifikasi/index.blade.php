<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klasifikasi DDC - SIPUSTAKA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false
            })
        })
    </script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased bg-[#F1F5F9]" x-data="">

    <div class="flex min-h-screen relative overflow-x-hidden">
        {{-- Pengkondisian Sidebar Sesuai Hak Akses User --}}
        @php
            $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan';
        @endphp
        @include($sidebar)

        {{-- Konten Utama Dashboard --}}
        <main class="flex-1 p-8 lg:p-12 overflow-y-auto custom-scrollbar w-full">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
                {{-- Bagian Judul + Tombol Pemicu Sidebar Mobile --}}
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <button @click="$store.sidebar.open = !$store.sidebar.open" 
                            class="lg:hidden p-3 bg-white text-slate-700 hover:text-blue-600 rounded-2xl shadow-sm border border-slate-200/60 transition active:scale-95 flex items-center justify-center shrink-0">
                        <i class="bi bi-list text-xl leading-none"></i>
                    </button>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-[900] uppercase italic tracking-tighter leading-none text-slate-800">
                            KLASIFIKASI <span class="text-blue-600">DDC & NO PANGGIL</span>
                        </h1>
                        <div class="h-1 w-20 bg-yellow-400 mt-2 rounded-full"></div>
                    </div>
                </div>
                
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-xl shadow-blue-500/20 flex items-center gap-2 ms-auto sm:ms-0">
                    <i class="bi bi-plus-lg"></i> Tambah Kelas
                </button>
            </div>

            {{-- Alert Notifikasi Sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 text-xs font-semibold">
                    <i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-black uppercase tracking-wider text-[10px]">
                                <th class="pb-4 w-16 text-center">No</th>
                                <th class="pb-4 w-32">Kode Klass</th>
                                <th class="pb-4">Nama Klass</th>
                                <th class="pb-4 text-center w-48">Warna Penanda</th>
                                <th class="pb-4 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-bold divide-y divide-slate-50">
                            
                            {{-- LOGIKA UTAMA: Loop Data Koleksi Master Klasifikasi dari Database --}}
                            @forelse($klasifikasi as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-4 font-black text-blue-600 text-sm tracking-wide">
                                        {{ $item->kode_klass }}
                                    </td>
                                    <td class="py-4 text-slate-600 font-semibold text-xs">
                                        {{ $item->nama_klass }}
                                    </td>
                                    <td class="py-4">
                                        <div class="flex justify-center items-center gap-2">
                                            <span class="w-4 h-4 rounded-full border border-slate-200/80 block shadow-sm" 
                                                  style="background-color: {{ $item->warna }};"></span>
                                            <code class="text-[10px] text-slate-400 font-mono uppercase">{{ $item->warna }}</code>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center">
                                        <div class="flex justify-center gap-1.5">
                                            <a href="{{ route('shared.klasifikasi.edit', $item->id) }}" 
                                               class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition text-xs shadow-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" 
                                                    class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition text-xs shadow-sm">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 italic font-semibold">
                                        <i class="bi bi-folder-x text-2xl block mb-2 text-slate-300"></i>
                                        Belum ada data klasifikasi DDC yang tersimpan di sistem.
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