<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - SIPUSTAKA ITH</title>
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
    openImport: false,
    editData: { id: null, name: '', nomor_identitas: '', prodi: '', angkatan: '' },
    search: '',
    filterVal: 'name_asc',
    allMahasiswa: {{ json_encode($mahasiswa) }},

    get filteredMahasiswa() {
        let data = [...this.allMahasiswa];
        
        if (this.search !== '') {
            const s = this.search.toLowerCase();
            data = data.filter(item =>
                (item.name && item.name.toLowerCase().includes(s)) ||
                (item.nomor_identitas && item.nomor_identitas.toString().includes(s))
            );
        }

        if (this.filterVal === 'name_asc') data.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
        else if (this.filterVal === 'name_desc') data.sort((a, b) => (b.name || '').localeCompare(a.name || ''));
        else if (this.filterVal === 'nim_asc') data.sort((a, b) => String(a.nomor_identitas).localeCompare(String(b.nomor_identitas), undefined, {numeric: true}));
        else if (this.filterVal === 'nim_desc') data.sort((a, b) => String(b.nomor_identitas).localeCompare(String(a.nomor_identitas), undefined, {numeric: true}));
        else if (this.filterVal.startsWith('prodi_')) {
            let p = this.filterVal.replace('prodi_', '');
            data = data.filter(item => item.prodi === p);
        }
        else if (this.filterVal.startsWith('year_')) {
            let y = this.filterVal.replace('year_', '');
            data = data.filter(item => String(item.angkatan) === y);
        }
        
        return data;
    },

    prepareEdit(mhs) {
        this.editData = { ...mhs };
        this.openEdit = true;
    }
}">

    <div class="flex min-h-screen">
        @if(Auth::user()->role == 'admin')
            @include('layouts.partials.sidebar-admin')
        @elseif(Auth::user()->role == 'pustakawan')
            @include('layouts.partials.sidebar-pustakawan')
        @endif

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-emerald-500 text-white p-4 rounded-2xl mb-6 shadow-lg shadow-emerald-100 flex items-center gap-3">
                    <i class="bi bi-check-circle-fill"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter">
                        Data <span class="text-blue-600">Mahasiswa</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-1 italic font-semibold">Kampus ITH Parepare</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button @click="openImport = true" class="bg-emerald-600 text-white px-5 py-3 rounded-2xl font-bold uppercase text-[10px] tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-100 flex items-center gap-2">
                        <i class="bi bi-file-earmark-excel-fill text-sm"></i> Import Excel
                    </button>
                    <a href="{{ route('shared.mahasiswa.export') }}" class="bg-white text-emerald-600 border border-emerald-100 px-5 py-3 rounded-2xl font-bold uppercase text-[10px] tracking-widest hover:bg-emerald-50 transition shadow-sm flex items-center gap-2">
                        <i class="bi bi-download text-sm"></i> Export
                    </a>
                    <button @click="openAdd = true" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold uppercase text-[10px] tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-100 flex items-center gap-2">
                        <i class="bi bi-plus-lg text-sm"></i> Tambah Baru
                    </button>
                </div>
            </header>

            {{-- Filters --}}
            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                        <i class="bi bi-search text-sm"></i>
                    </span>
                    <input type="text" x-model="search"
                        class="w-full bg-white border border-slate-200 rounded-[1.5rem] py-4 pl-14 pr-4 text-sm font-bold text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm"
                        placeholder="Ketik Nama atau NIM mahasiswa...">
                </div>

                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                        <i class="bi bi-filter-circle-fill text-lg"></i>
                    </span>
                    <select x-model="filterVal"
                        class="w-full bg-white border border-slate-200 rounded-[1.5rem] py-4 pl-14 pr-10 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm appearance-none cursor-pointer">
                        <optgroup label="Urutan Dasar">
                            <option value="name_asc">Urutkan: Nama A - Z</option>
                            <option value="name_desc">Urutkan: Nama Z - A</option>
                            <option value="nim_asc">Urutkan: NIM Terkecil</option>
                            <option value="nim_desc">Urutkan: NIM Terbesar</option>
                        </optgroup>
                        <optgroup label="Filter Program Studi">
                            @foreach($mahasiswa->unique('prodi')->pluck('prodi') as $prodi)
                                <option value="prodi_{{ $prodi }}">Prodi: {{ $prodi }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center w-16">No</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Mahasiswa</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Prodi & Angkatan</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="(mhs, index) in filteredMahasiswa" :key="mhs.id">
                                <tr class="hover:bg-slate-50/50 transition duration-200">
                                    <td class="px-6 py-6 text-center text-xs font-black text-slate-400" x-text="index + 1"></td>
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-slate-700 text-sm capitalize" x-text="mhs.name"></p>
                                        <p class="text-[10px] text-slate-400 font-black mt-1 uppercase">NIM: <span x-text="mhs.nomor_identitas"></span></p>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="text-[11px] font-bold text-slate-700 block" x-text="mhs.prodi"></span>
                                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Angkatan <span x-text="mhs.angkatan || '-'"></span></span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button @click="prepareEdit(mhs)"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-blue-600 hover:text-white transition-all">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form :action="`/admin/mahasiswa/${mhs.id}`" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
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

    {{-- Modal Import (Sudah Ada) --}}
    <div x-show="openImport" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden" @click.away="openImport = false">
            <div class="bg-emerald-600 p-6">
                <h3 class="text-white font-extrabold flex items-center gap-3 uppercase tracking-tighter italic">
                    <i class="bi bi-file-earmark-excel-fill text-xl"></i> Import Mahasiswa
                </h3>
            </div>
            <form action="{{ route('shared.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="mb-6">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pilih File Master Excel</label>
                    <input type="file" name="file_excel" required 
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-100 rounded-2xl p-2 bg-slate-50/50">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="openImport = false" class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 rounded-2xl transition">Batal</button>
                    <button type="submit" class="px-8 py-3 text-[10px] font-black uppercase text-white bg-emerald-600 rounded-2xl shadow-lg transition">Upload</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tambah (Fungsional) --}}
    <div x-show="openAdd" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden" x-data="{ nim: '', get angkatan() { return this.nim.length >= 2 ? '20' + this.nim.substring(0, 2) : ''; } }">
            <div class="bg-blue-600 p-8 text-white">
                <h3 class="font-extrabold flex items-center gap-3 uppercase tracking-tighter italic text-xl">
                    <i class="bi bi-person-plus-fill"></i> Tambah Mahasiswa
                </h3>
            </div>
            <form action="{{ route('shared.mahasiswa.store') }}" method="POST" class="p-10 space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold outline-blue-500 transition-all shadow-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">NIM</label>
                        <input type="number" name="nomor_identitas" x-model="nim" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold outline-blue-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Angkatan</label>
                        <input type="text" name="angkatan" :value="angkatan" readonly class="w-full bg-slate-100 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-black text-blue-600 cursor-not-allowed shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Program Studi</label>
                    <select name="prodi" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold outline-blue-500 shadow-sm appearance-none">
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Informatika">Informatika</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="Matematika">Matematika</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="openAdd = false" class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 hover:bg-slate-50 rounded-2xl transition">Batal</button>
                    <button type="submit" class="px-10 py-4 text-[10px] font-black uppercase text-white bg-blue-600 hover:bg-blue-700 rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center gap-3">
                        <i class="bi bi-cloud-check-fill text-sm"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit (Fungsional) --}}
    <div x-show="openEdit" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden">
            <div class="bg-slate-800 p-8 text-white">
                <h3 class="font-extrabold flex items-center gap-3 uppercase tracking-tighter italic text-xl">
                    <i class="bi bi-pencil-square text-blue-400"></i> Perbarui Data
                </h3>
            </div>
            <form :action="`/admin/mahasiswa/${editData.id}`" method="POST" class="p-10 space-y-6">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold outline-blue-500 shadow-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">NIM</label>
                        <input type="number" name="nomor_identitas" x-model="editData.nomor_identitas" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold outline-blue-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Angkatan</label>
                        <input type="text" name="angkatan" x-model="editData.angkatan" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold outline-blue-500 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Program Studi</label>
                    <select name="prodi" x-model="editData.prodi" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-6 text-sm font-bold outline-blue-500 shadow-sm appearance-none">
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Informatika">Informatika</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="Matematika">Matematika</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="openEdit = false" class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 hover:bg-slate-50 rounded-2xl transition">Tutup</button>
                    <button type="submit" class="px-10 py-4 text-[10px] font-black uppercase text-white bg-slate-800 hover:bg-slate-900 rounded-2xl shadow-xl transition-all flex items-center gap-3">
                        <i class="bi bi-arrow-repeat text-sm text-blue-400"></i> Update Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>