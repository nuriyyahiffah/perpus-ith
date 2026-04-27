<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Anggota - SIPUSTAKA ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-item-active { background: rgba(255, 255, 255, 0.1); border-left: 4px solid #FACC15; color: white !important; }
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F1F5F9] antialiased" x-data="{ openAdd: false, openEdit: false, currentData: {} }">

    <div class="flex min-h-screen">
        @if(Auth::user()->role == 'admin')
            @include('layouts.partials.sidebar-admin')
        @else
            @include('layouts.partials.sidebar-pustakawan')
        @endif

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter">
                        Kategori <span class="text-blue-600">Anggota</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">Kelola batas peminjaman dan durasi berdasarkan kategori anggota.</p>
                </div>
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase text-slate-400">Administrator</p>
                        <p class="text-xs font-bold text-slate-700">{{ Auth::user()->name ?? 'Admin ITH' }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="bi bi-person-badge-fill text-xl"></i>
                    </div>
                </div>
            </header>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white">
                    <h2 class="font-black uppercase text-[11px] tracking-widest text-slate-600">Daftar Kategori Aktif</h2>
                    <button @click="openAdd = true" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                        + Tambah Kategori
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Nama Kategori</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Maks. Pinjam</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Durasi Pinjam</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($kategori as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-8 py-6">
                                    <span class="font-bold text-slate-700 text-sm">{{ $item->nama_kategori }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-[11px] font-black uppercase italic">
                                        {{ $item->maksimal_pinjam }} Buku
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-sm font-black text-orange-500 italic">{{ $item->durasi_pinjam }} Hari</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex justify-center gap-4">
                                        <button @click="currentData = {{ json_encode($item) }}; openEdit = true" class="text-blue-600 hover:scale-110 transition text-lg">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('shared.kategori-anggota.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="text-red-500 hover:scale-110 transition text-lg">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div x-show="openAdd" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl" @click.away="openAdd = false">
            <h3 class="text-xl font-black text-slate-800 mb-8 italic uppercase tracking-tighter">Tambah <span class="text-blue-600">Kategori</span></h3>
            <form action="{{ route('shared.kategori-anggota.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block">Nama Kategori</label>
                    <input type="text" name="nama_kategori" placeholder="Contoh: Mahasiswa Reguler" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block">Maks. Buku</label>
                        <input type="number" name="maksimal_pinjam" placeholder="0" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block">Durasi (Hari)</label>
                        <input type="number" name="durasi_pinjam" placeholder="0" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="openAdd = false" class="flex-1 py-4 text-xs font-black uppercase text-slate-400 hover:text-slate-600 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-100 hover:bg-blue-700 transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEdit" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl" @click.away="openEdit = false">
            <h3 class="text-xl font-black text-slate-800 mb-8 italic uppercase tracking-tighter">Edit <span class="text-blue-600">Kategori</span></h3>

            <form :action="`{{ url('admin/kategori-anggota') }}/${currentData.id}`" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block">Nama Kategori</label>
                    <input type="text" name="nama_kategori" x-model="currentData.nama_kategori" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block">Maks. Buku</label>
                        <input type="number" name="maksimal_pinjam" x-model="currentData.maksimal_pinjam" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1 block">Durasi (Hari)</label>
                        <input type="number" name="durasi_pinjam" x-model="currentData.durasi_pinjam" class="w-full bg-slate-100 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="openEdit = false" class="flex-1 py-4 text-xs font-black uppercase text-slate-400 hover:text-slate-600 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-100 hover:bg-blue-700 transition">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-10 right-10 bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 z-[60] transition-all">
        <i class="bi bi-check-circle-fill text-xl"></i>
        <span class="text-xs font-bold uppercase tracking-wider">{{ session('success') }}</span>
    </div>
    @endif

</body>
</html>
