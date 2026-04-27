<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <title>Kelola Kategori - ITH Lib</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* CSS ini tetap ada untuk styling sidebar yang di-include */
        .sidebar-item-active { background-color: rgba(59, 130, 246, 0.1); border-color: #3b82f6; color: #3b82f6; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-700">
    <div class="flex">
        @if(Auth::user()->role == 'admin')
            @include('layouts.partials.sidebar-admin')
        @else
            @include('layouts.partials.sidebar-pustakawan')
        @endif

        <main class="flex-1 p-8 lg:p-12">

            <div class="flex justify-end mb-6">
                <div class="bg-white px-4 py-2 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[9px] font-black uppercase text-slate-400 leading-none">{{ Auth::user()->role }}</p>
                        <p class="text-xs font-bold text-slate-700">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-person-circle text-xl"></i>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-8 shadow-sm flex items-center gap-3">
                    <i class="bi bi-check2-circle text-xl"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-10">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase italic">
                    Kelola <span class="text-blue-600">Kategori Buku</span>
                </h1>
                <p class="text-slate-500 text-sm mt-1">Tambahkan kategori untuk mengelompokkan koleksi buku perpustakaan.</p>
            </div>

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 mb-10 transition-all hover:shadow-md">
                <form action="{{ route('shared.kategori-buku.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                    @csrf
                    <div class="flex-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Nama Kategori Baru</label>
                        <input type="text" name="nama_kategori" required placeholder="Contoh: Informatika, Sains, Sejarah..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition">
                    </div>
                    <div class="md:self-end">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-200 h-[52px]">
                            <i class="bi bi-plus-lg me-2"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                            <th class="p-6 w-24">ID</th>
                            <th class="p-6">Nama Kategori</th>
                            <th class="p-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($kategori as $k)
                        <tr class="hover:bg-slate-50/50 transition group">
                            <td class="p-6">
                                <span class="text-xs font-mono font-bold bg-slate-100 px-3 py-1 rounded-lg text-slate-500">
                                    #{{ $k->id }}
                                </span>
                            </td>
                            <td class="p-6">
                                <span class="font-bold text-slate-800 text-sm italic">{{ $k->nama_kategori }}</span>
                            </td>
                            <td class="p-6 text-center">
                                <form action="{{ route('shared.kategori-buku.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase transition tracking-wider">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-tags text-6xl text-slate-200"></i>
                                    <p class="mt-4 text-slate-400 italic text-sm font-medium">Belum ada kategori yang dibuat.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
