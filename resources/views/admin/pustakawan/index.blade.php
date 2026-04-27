<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pustakawan - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased">

    <div class="flex min-h-screen">
        @include('layouts.partials.sidebar-admin')

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                        Data <span class="text-blue-600">Pustakawan</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-2 font-semibold italic">Manajemen Staf Pengelola Perpustakaan ITH</p>
                </div>

                <button class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-blue-500/20 active:scale-95">
                    <i class="bi bi-person-plus-fill text-sm"></i>
                    Tambah Pustakawan
                </button>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Total Staf</p>
                        <p class="text-xl font-black text-slate-800">{{ $pustakawan->count() }} Orang</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden" x-data="{ search: '' }">
                <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row justify-between gap-4 bg-slate-50/30">
                    <div class="relative w-full md:w-96">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                            <i class="bi bi-search text-xs"></i>
                        </span>
                        <input type="text" x-model="search"
                            class="w-full bg-white border border-slate-200 rounded-2xl py-3.5 pl-12 pr-4 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                            placeholder="Cari Pustakawan...">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Informasi Pustakawan</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Kontak & Email</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pustakawan as $p)
                            <tr class="hover:bg-slate-50/50 transition duration-200"
                                x-show="'{{ strtolower($p->name) }}'.includes(search.toLowerCase())">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-11 h-11 bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 rounded-2xl flex items-center justify-center font-black text-sm shadow-sm border border-white">
                                            {{ strtoupper(substr($p->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700 text-sm uppercase leading-tight">{{ $p->name }}</p>
                                            <p class="text-[10px] text-blue-600 font-black mt-1 tracking-wider italic">ID: {{ $p->nip ?? 'PS-'.str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <i class="bi bi-envelope-at text-xs"></i>
                                            <span class="text-xs font-bold">{{ $p->email }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-emerald-500">
                                            <i class="bi bi-whatsapp text-xs"></i>
                                            <span class="text-[10px] font-black italic">{{ $p->no_hp ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center gap-2">
                                        <button title="Edit Data" class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('admin.pustakawan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pustakawan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                <i class="bi bi-trash3-fill text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-person-exclamation text-5xl text-slate-200 mb-4"></i>
                                        <p class="text-slate-400 font-bold text-sm uppercase italic">Belum ada data pustakawan.</p>
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
