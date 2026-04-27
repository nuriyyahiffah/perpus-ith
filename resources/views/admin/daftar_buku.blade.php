<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <title>Daftar Koleksi Buku - ITH Lib</title>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] text-slate-700">
    <div class="flex">
        <div class="w-64 h-screen bg-[#1E293B] p-6 sticky top-0 shadow-xl flex flex-col">
            <div class="mb-10 text-center">
                <h2 class="text-xl font-black text-yellow-500 tracking-tighter uppercase italic">PERPUSTAKAAN <span class="text-white">ITH</span></h2>
            </div>
            <nav class="space-y-2 flex-1">
                <a href="{{ route('pustakawan.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-xl transition text-slate-400 hover:bg-white/5 hover:text-white text-sm font-semibold">
                    <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
                </a>
                <a href="{{ route('pustakawan.buku.index') }}" class="flex items-center space-x-3 p-3 rounded-xl transition bg-blue-600/10 text-blue-500 border-l-4 border-blue-500 text-sm font-bold">
                    <i class="bi bi-journal-text"></i> <span>Daftar Koleksi</span>
                </a>
                <a href="{{ route('pustakawan.buku.create') }}" class="flex items-center space-x-3 p-3 rounded-xl transition text-slate-400 hover:bg-white/5 hover:text-white text-sm font-semibold">
                    <i class="bi bi-plus-circle"></i> <span>Tambah Buku</span>
                </a>
            </nav>
        </div>

        <main class="flex-1 p-8 lg:p-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Koleksi Buku</h1>
                    <p class="text-slate-500 text-sm">Total {{ $semua_buku->count() }} judul buku terdaftar</p>
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('pustakawan.buku.index') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul atau Kode..."
                            class="bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 w-64 shadow-sm outline-none">
                        <button type="submit" class="absolute right-4 top-3.5 text-slate-400 hover:text-blue-500">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('pustakawan.buku.create') }}" class="bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
                        + Tambah Baru
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="p-6 text-slate-400 uppercase text-[10px] font-black tracking-widest">Buku</th>
                                <th class="p-6 text-slate-400 uppercase text-[10px] font-black tracking-widest">Kategori & Stok</th>
                                <th class="p-6 text-center text-slate-400 uppercase text-[10px] font-black tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($semua_buku as $b)
                            <tr class="hover:bg-slate-50/30 transition group">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ asset('images/' . $b->gambar_buku) }}" class="w-12 h-16 object-cover rounded-xl shadow-md">
                                        <div>
                                            <span class="text-[10px] font-mono font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded mb-1 inline-block uppercase">{{ $b->kode_buku }}</span>
                                            <div class="font-bold text-slate-800 text-sm">{{ $b->judul }}</div>
                                            <div class="text-[11px] text-slate-400 font-medium">{{ $b->penulis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex flex-col gap-2">
                                        <span class="text-xs font-semibold text-slate-600 italic">
                                            <i class="bi bi-tag me-1"></i> {{ $b->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-blue-500 h-full" style="width: {{ $b->stok > 10 ? '100' : $b->stok * 10 }}%"></div>
                                            </div>
                                            <span class="text-[11px] font-bold {{ $b->stok > 0 ? 'text-blue-600' : 'text-red-500' }}">
                                                {{ $b->stok }} Eks
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('pustakawan.buku.edit', $b->id) }}" class="p-2.5 bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white rounded-xl transition shadow-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('pustakawan.buku.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-slate-50 text-slate-400 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-20 text-center text-slate-400 italic">Data buku tidak ditemukan.</td>
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
