<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai - SIPUSTAKA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased" x-data="{ openModal: false, search: '' }">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @php $sidebar = Auth::user()->role == 'admin' ? 'layouts.partials.sidebar-admin' : 'layouts.partials.sidebar-pustakawan'; @endphp
        @include($sidebar)

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            {{-- Header --}}
            <header class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                        Data <span class="text-blue-600">Pegawai</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-2 font-semibold italic">Manajemen Pengguna SIPUSTAKA ITH</p>
                </div>

                <button @click="openModal = true" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-blue-500/20 active:scale-95">
                    <i class="bi bi-person-plus-fill"></i> Tambah Pegawai
                </button>
            </header>

            {{-- Alert --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl text-xs font-bold flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-lg"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Table --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <div class="relative w-full md:w-96">
                        <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" class="w-full bg-white border border-slate-200 rounded-2xl py-3.5 pl-12 pr-6 text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all" placeholder="Cari nama atau NIP...">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Pegawai</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">NIP</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">Jabatan</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pegawai as $p)
                            <tr class="hover:bg-slate-50/50 transition" x-show="'{{ strtolower($p->nama) }} {{ $p->nip }}'.includes(search.toLowerCase())">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">
                                            {{ substr($p->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 uppercase leading-tight">{{ $p->nama }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold mt-1">{{ $p->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center font-mono text-[11px] font-bold text-slate-600">{{ $p->nip }}</td>
                                <td class="px-8 py-6 text-center">
                                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[9px] font-black border border-blue-100 uppercase">{{ $p->jabatan }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('shared.pegawai.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pegawai?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all shadow-sm"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold italic uppercase">Data Kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH PEGAWAI --}}
    <div x-show="openModal" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden" @click.away="openModal = false">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-xl font-black text-slate-800 uppercase italic">Tambah <span class="text-blue-600">Pegawai Baru</span></h2>
                <button @click="openModal = false" class="text-slate-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('shared.pegawai.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NIP</label>
                        <input type="text" name="nip" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jabatan</label>
                        <select name="jabatan" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-5 text-sm font-bold outline-none">
                            <option value="Pustakawan">Pustakawan</option>
                            <option value="Staf Administrasi">Staf Administrasi</option>
                            <option value="Kepala Perpustakaan">Kepala Perpustakaan</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email ITH</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="openModal = false" class="px-6 py-3 text-[10px] font-black uppercase text-slate-400">Batal</button>
                    <button type="submit" class="px-10 py-3 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-500/20">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>