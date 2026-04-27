<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-item-active { background: rgba(255, 255, 255, 0.1); border-left: 4px solid #FACC15; color: white !important; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased" x-data="{ 
    openAdd: false, 
    search: '',
    allDosen: {{ json_encode($dosen) }},
    
    get filteredDosen() {
        if (this.search === '') return this.allDosen;
        return this.allDosen.filter(d => 
            d.name.toLowerCase().includes(this.search.toLowerCase()) || 
            d.nomor_identitas.includes(this.search)
        );
    }
}">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @if(Auth::user()->role == 'admin')
            @include('layouts.partials.sidebar-admin')
        @elseif(Auth::user()->role == 'pustakawan')
            @include('layouts.partials.sidebar-pustakawan')
        @endif

        {{-- Toast Success --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-10 left-1/2 -translate-x-1/2 z-[100] bg-slate-900 text-white px-8 py-4 rounded-3xl shadow-2xl font-bold flex items-center gap-4 border border-slate-700">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                    <i class="bi bi-check-lg text-white"></i>
                </div>
                {{ session('success') }}
            </div>
        @endif

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            {{-- Header --}}
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter">
                        Data <span class="text-blue-600">Dosen</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-1 italic font-semibold">
                        Daftar Pengajar & Staf Akademik Institut Teknologi BJ Habibie
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block border-r border-slate-200 pr-4">
                        <p class="text-[10px] font-black uppercase text-slate-400">Dosen Aktif</p>
                        <p class="text-xs font-bold text-slate-700" x-text="allDosen.length + ' Orang'"></p>
                    </div>
                    <button @click="openAdd = true" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-800 transition shadow-lg shadow-blue-100">
                        + Tambah Dosen Baru
                    </button>
                </div>
            </header>

            {{-- Table Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                {{-- Search Bar --}}
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <div class="relative max-w-md">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari Nama atau NIP/NIDN..."
                            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition text-sm text-slate-700 font-medium">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Identitas Dosen</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Program Studi</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Email</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="row in filteredDosen" :key="row.id">
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                                                <i class="bi bi-person-badge-fill text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-700 text-sm" x-text="row.name"></div>
                                                <div class="text-[11px] text-blue-500 font-bold tracking-wider uppercase" x-text="row.nomor_identitas"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="text-xs font-bold text-slate-600 uppercase" x-text="row.prodi || 'Umum'"></div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="text-[11px] text-slate-400 font-medium italic" x-text="row.email"></div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <template x-if="row.status_akun == 'aktif'">
                                            <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">Aktif</span>
                                        </template>
                                        <template x-if="row.status_akun != 'aktif'">
                                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter italic font-bold">Suspended</span>
                                        </template>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex justify-end gap-3 text-lg">
                                            <button class="text-blue-500 hover:scale-110 transition"><i class="bi bi-pencil-square"></i></button>
                                            <button class="text-red-400 hover:scale-110 transition"><i class="bi bi-trash3-fill"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    {{-- Empty State --}}
                    <div x-show="filteredDosen.length === 0" class="px-8 py-20 text-center" x-cloak>
                        <div class="flex flex-col items-center">
                            <i class="bi bi-person-slash text-5xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em]">Data Dosen ITH Tidak Ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Tambah Dosen --}}
    <div x-show="openAdd" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl overflow-y-auto max-h-[90vh]" @click.away="openAdd = false">
            <h3 class="text-xl font-black text-slate-800 mb-8 italic uppercase tracking-tighter">Registrasi <span class="text-blue-600">Dosen Baru</span></h3>

            <form action="{{ route('shared.dosen.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block tracking-widest">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-slate-300" placeholder="Contoh: Dr. Habibie, S.T., M.T." required>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block tracking-widest">NIDN / NIP</label>
                        <input type="text" name="nomor_identitas" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-slate-300" placeholder="Masukkan Nomor Identitas..." required>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block tracking-widest">Homebase Program Studi</label>
                        <div class="relative">
                            <select name="prodi" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                                <option value="Ilmu Komputer">Ilmu Komputer</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Matematika">Matematika</option>
                            </select>
                            <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block tracking-widest">Email Instansi (@ith.ac.id)</label>
                        <input type="email" name="email" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-slate-300" placeholder="nama@ith.ac.id" required>
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="button" @click="openAdd = false" class="flex-1 py-4 text-xs font-black uppercase text-slate-400 hover:text-slate-600 transition tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-100 hover:bg-slate-800 transition">Daftarkan Dosen</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>