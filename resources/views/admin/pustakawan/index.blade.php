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
<body class="bg-[#F1F5F9] antialiased" x-data="{ openModal: false, openEditModal: false, search: '', editData: {}, editAction: '' }">

    <div class="flex min-h-screen">
        @include('layouts.partials.sidebar-admin')

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">

            {{-- Menampilkan alert jika sukses menyimpan --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl text-xs font-bold flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-sm"></i>
                    {{ session('success') }}
                </div>
            @endif

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter leading-none">
                        Data <span class="text-blue-600">Pustakawan</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-2 font-semibold italic">Manajemen Staf Pengelola Perpustakaan ITH</p>
                </div>

                <button @click="openModal = true" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-blue-500/20 active:scale-95">
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

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
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
                                            <p class="text-[10px] text-blue-600 font-black mt-1 tracking-wider italic">ID / NIP: {{ $p->nip ?? 'PS-'.str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</p>
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
                                        <button title="Edit Data"
                                            @click="
                                                editData = {
                                                    name: '{{ $p->name }}',
                                                    nip: '{{ $p->nip }}',
                                                    email: '{{ $p->email }}',
                                                    no_hp: '{{ $p->no_hp }}'
                                                };
                                                editAction = '{{ route('admin.pustakawan.update', $p->id) }}';
                                                openEditModal = true;
                                            "
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
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

    {{-- ================= FORM MODAL TAMBAH PUSTAKAWAN (ALPINJS) ================= --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/50 backdrop-blur-sm"
         x-show="openModal" x-cloak x-transition>

        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl border border-slate-100 m-4"
             @click.away="openModal = false">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-[900] text-slate-800 uppercase tracking-tight">Tambah <span class="text-blue-600">Pustakawan</span></h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>

            <form action="{{ route('admin.pustakawan.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">NIP / Nomor Identitas</label>
                        <input type="text" name="nip" placeholder="Contoh: 1995..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Alamat Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nomor WhatsApp</label>
                        <input type="text" name="no_hp" placeholder="08..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Password Login</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-blue-500/20">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ================= FORM MODAL EDIT PUSTAKAWAN (BARU) ================= --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/50 backdrop-blur-sm"
         x-show="openEditModal" x-cloak x-transition>

        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl border border-slate-100 m-4"
             @click.away="openEditModal = false">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-[900] text-slate-800 uppercase tracking-tight">Edit <span class="text-blue-600">Pustakawan</span></h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>

            <form :action="editAction" method="POST">
                @csrf
                @method('PUT') {{-- Menggunakan PUT untuk rute update Laravel --}}

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editData.name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">NIP / Nomor Identitas</label>
                        <input type="text" name="nip" x-model="editData.nip" placeholder="Contoh: 1995..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Alamat Email</label>
                        <input type="email" name="email" x-model="editData.email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Nomor WhatsApp</label>
                        <input type="text" name="no_hp" x-model="editData.no_hp" placeholder="08..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-[10px] text-amber-700 font-bold uppercase tracking-wide">
                        <i class="bi bi-info-circle-fill me-1"></i> Biarkan input password kosong jika tidak ingin menggantinya.
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Ganti Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Isi jika ingin ganti password" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="openEditModal = false" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-blue-500/20">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
