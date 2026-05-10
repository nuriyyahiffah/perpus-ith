<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dosen & Kaprodi - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased" x-data="{
    openAdd: false,
    openEdit: false,
    search: '',
    allDosen: {{ json_encode($dosen) }},
    editData: { id: '', name: '', nomor_identitas: '', prodi: '', email: '', role: 'dosen', no_telp: '' },

    get filteredDosen() {
        if (this.search === '') return this.allDosen;
        return this.allDosen.filter(d =>
            d.name.toLowerCase().includes(this.search.toLowerCase()) ||
            d.nomor_identitas.includes(this.search) ||
            (d.prodi && d.prodi.toLowerCase().includes(this.search.toLowerCase()))
        );
    },

    triggerEdit(dosen) {
        this.editData = { ...dosen };
        this.openEdit = true;
    }
}">

    <div class="flex min-h-screen">
        {{-- Sidebar dinamis berdasarkan role --}}
        @include('layouts.partials.sidebar-' . Auth::user()->role)

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            {{-- Header --}}
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter">
                        Manajemen <span class="text-blue-600">Dosen & Kaprodi</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-1 italic font-semibold">
                        Otoritas akses pengajar Institut Teknologi BJ Habibie
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <button @click="openAdd = true" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-800 transition shadow-lg shadow-blue-100">
                        + Tambah Pengajar Baru
                    </button>
                </div>
            </header>

            {{-- Table --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <div class="relative max-w-md">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari Nama, NIDN, atau Prodi..."
                            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition text-sm text-slate-700 font-medium">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Nama & Identitas</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Jabatan</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Prodi</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Kontak</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="row in filteredDosen" :key="row.id">
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div :class="row.role === 'kaprodi' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600'" class="w-10 h-10 rounded-xl flex items-center justify-center">
                                                <i :class="row.role === 'kaprodi' ? 'bi bi-award-fill' : 'bi bi-person-badge-fill'" class="text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-700 text-sm" x-text="row.name"></div>
                                                <div class="text-[11px] text-slate-400 font-bold tracking-wider uppercase" x-text="row.nomor_identitas"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span :class="row.role === 'kaprodi' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200'"
                                              class="text-[9px] font-black uppercase px-3 py-1 rounded-full border"
                                              x-text="row.role"></span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="text-xs font-bold text-slate-600 uppercase" x-text="row.prodi || '-'"></div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="text-[11px] text-slate-500 font-medium" x-text="row.email"></div>
                                        <div class="text-[10px] text-emerald-600 font-bold" x-text="row.no_telp || '-'"></div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex justify-end gap-3 text-lg">
                                            <button @click="triggerEdit(row)" class="text-blue-500 hover:scale-110 transition"><i class="bi bi-pencil-square"></i></button>

                                            {{-- PERBAIKAN URL ACTION HAPUS --}}
                                            <form :action="'{{ url('kelola/dosen') }}/' + row.id" method="POST" onsubmit="return confirm('Hapus data pengajar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-400 hover:scale-110 transition"><i class="bi bi-trash3-fill"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Tambah --}}
    <div x-show="openAdd" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl overflow-y-auto max-h-[90vh]">
            <h3 class="text-xl font-black text-slate-800 mb-8 italic uppercase tracking-tighter">Registrasi <span class="text-blue-600">Pengajar</span></h3>
            <form action="{{ route('shared.dosen.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Jabatan Otoritas</label>
                        <select name="role" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="dosen">Dosen Biasa</option>
                            <option value="kaprodi">Kaprodi (Kepala Program Studi)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required placeholder="Dr. Ir. Habibie, M.T.">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">NIDN / NIP</label>
                            <input type="text" name="nomor_identitas" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">No. WhatsApp</label>
                            <input type="text" name="no_telp" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" placeholder="08...">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Program Studi</label>
                        <select name="prodi" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Ilmu Komputer">Ilmu Komputer</option>
                            <option value="Sains Data">Sains Data</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Email Kampus</label>
                        <input type="email" name="email" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>
                <div class="flex gap-4 pt-6">
                    <button type="button" @click="openAdd = false" class="flex-1 py-4 text-xs font-black uppercase text-slate-400 tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg hover:bg-slate-800 transition">Daftarkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="openEdit" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl overflow-y-auto max-h-[90vh]">
            <h3 class="text-xl font-black text-slate-800 mb-8 italic uppercase tracking-tighter">Update <span class="text-blue-600">Data</span></h3>

            {{-- PERBAIKAN URL ACTION EDIT --}}
            <form :action="'{{ url('kelola/dosen') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Jabatan</label>
                        <select name="role" x-model="editData.role" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="dosen">Dosen Biasa</option>
                            <option value="kaprodi">Kaprodi</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editData.name" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">NIDN / NIP</label>
                            <input type="text" name="nomor_identitas" x-model="editData.nomor_identitas" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">No. WhatsApp</label>
                            <input type="text" name="no_telp" x-model="editData.no_telp" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Program Studi</label>
                        <select name="prodi" x-model="editData.prodi" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Ilmu Komputer">Ilmu Komputer</option>
                            <option value="Sains Data">Sains Data</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                        </select>
                    </div>
                    {{-- TAMBAHKAN INPUT EMAIL DI EDIT AGAR VALIDASI TIDAK ERROR --}}
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block tracking-widest ml-2">Email Kampus</label>
                        <input type="email" name="email" x-model="editData.email" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>
                <div class="flex gap-4 pt-6">
                    <button type="button" @click="openEdit = false" class="flex-1 py-4 text-xs font-black uppercase text-slate-400 tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 bg-slate-800 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg hover:bg-blue-600 transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-[#2D3E50] text-white p-6 mt-10">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-8">
                <span class="text-[10px] font-bold uppercase tracking-wider">SIPUSTAKA <span class="text-yellow-400">ITH</span></span>
            </div>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">&copy; {{ date('Y') }} Institut Teknologi BJ Habibie.</div>
        </div>
    </footer>

</body>
</html>
