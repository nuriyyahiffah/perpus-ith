<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <title>Kelola Pengumuman - ITH Lib</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-item-active { background-color: rgba(59, 130, 246, 0.1); border-color: #3b82f6; color: #3b82f6; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-700">
    <div class="flex">
        <div class="w-64 h-screen bg-[#1E293B] p-6 sticky top-0 shadow-xl flex flex-col">
            <div class="mb-10">
                <h2 class="text-xl font-black text-yellow-500 tracking-tighter uppercase italic">ITH <span class="text-white">LIBRARY</span></h2>
                <p class="text-[9px] text-slate-400 font-bold tracking-[0.2em] uppercase">Panel Pustakawan</p>
            </div>

            <nav class="space-y-2 flex-1">
                <a href="{{ route('pustakawan.dashboard') }}"
                    class="flex items-center space-x-3 p-3 rounded-xl transition text-slate-400 hover:bg-white/5 hover:text-white text-sm font-semibold">
                    <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
                </a>

                <a href="{{ route('buku.create') }}"
                    class="flex items-center space-x-3 p-3 rounded-xl transition text-slate-400 hover:bg-white/5 hover:text-white text-sm font-semibold">
                    <i class="bi bi-plus-circle"></i> <span>Tambah Buku</span>
                </a>

                <a href="{{ route('kategori.index') }}"
                    class="flex items-center space-x-3 p-3 rounded-xl transition text-slate-400 hover:bg-white/5 hover:text-white text-sm font-semibold">
                    <i class="bi bi-tags"></i> <span>Kelola Kategori</span>
                </a>

                <a href="{{ route('pengumuman.index') }}"
                    class="flex items-center space-x-3 p-3 rounded-xl transition font-bold text-sm sidebar-item-active border-l-4">
                    <i class="bi bi-megaphone-fill"></i> <span>Pengumuman</span>
                </a>

                <hr class="border-white/5 my-6">

                <a href="{{ route('beranda') }}"
                    class="flex items-center space-x-3 p-3 rounded-xl transition text-slate-500 hover:text-blue-400 text-sm font-medium">
                    <i class="bi bi-house"></i> <span>Beranda Utama</span>
                </a>
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t border-white/5">
                @csrf
                <button type="submit"
                    class="w-full text-left p-3 rounded-xl text-red-400 hover:bg-red-500/10 transition text-xs font-black uppercase tracking-widest">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>

        <main class="flex-1 p-8 lg:p-12">
            @if (session('success'))
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-xl mb-8 shadow-sm">
                    <span class="font-bold">✨ {{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-10">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Kelola Pengumuman</h1>
                <p class="text-slate-500 text-sm">Publikasikan informasi atau poster terbaru untuk mahasiswa</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-5">
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 sticky top-12">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="bi bi-cloud-arrow-up text-blue-600"></i> Unggah Poster Baru
                        </h3>

                        <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Judul / Keterangan</label>
                                <input type="text" name="judul" required placeholder="Contoh: Perpus Tutup Libur Lebaran"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition">
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Pilih File Poster</label>
                                <input type="file" name="gambar" id="imageInput" accept="image/*" required
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-2 px-1 italic">* Maksimal 2MB, disarankan rasio 4:5</p>
                            </div>

                            <div id="previewContainer" class="hidden animate-pulse">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Preview:</label>
                                <img id="imagePreview" src="#" alt="Preview" class="w-full rounded-2xl border-2 border-dashed border-slate-200 p-2 shadow-inner h-64 object-cover">
                            </div>

                            <button type="submit" class="w-full bg-[#2D3E50] text-white py-4 rounded-2xl font-bold text-xs uppercase hover:bg-blue-700 transition shadow-lg shadow-blue-900/10 flex items-center justify-center gap-2">
                                <i class="bi bi-send-fill"></i> Publikasikan Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 text-sm">Riwayat Pengumuman</h3>
                            <span class="bg-blue-100 text-blue-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">{{ $pengumumans->count() }} Total</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-slate-400 uppercase text-[9px] font-black tracking-[0.2em] border-b border-slate-50">
                                        <th class="p-6">Poster</th>
                                        <th class="p-6">Detail Pengumuman</th>
                                        <th class="p-6 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-sm">
                                    @forelse($pengumumans as $p)
                                    <tr class="hover:bg-slate-50/30 transition group">
                                        <td class="p-6">
                                            <img src="{{ asset('storage/' . $p->gambar) }}"
                                                class="w-14 h-14 object-cover rounded-xl shadow-sm group-hover:scale-110 transition duration-300">
                                        </td>
                                        <td class="p-6">
                                            <p class="font-bold text-slate-800 mb-1">{{ $p->judul }}</p>
                                            <p class="text-[11px] text-slate-400 font-medium">
                                                <i class="bi bi-calendar3 me-1"></i> {{ $p->created_at->format('d M Y') }}
                                            </p>
                                        </td>
                                        <td class="p-6 text-center">
                                            <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white w-10 h-10 rounded-xl flex items-center justify-center transition">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="p-20 text-center">
                                            <i class="bi bi-megaphone text-5xl text-slate-100"></i>
                                            <p class="mt-4 text-slate-400 italic text-sm">Belum ada pengumuman yang aktif.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewContainer = document.getElementById('previewContainer');

        imageInput.onchange = evt => {
            const [file] = imageInput.files
            if (file) {
                imagePreview.src = URL.createObjectURL(file)
                previewContainer.classList.remove('hidden')
            }
        }
    </script>
</body>
</html>
