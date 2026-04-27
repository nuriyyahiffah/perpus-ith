<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usulan Buku - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="antialiased bg-slate-50">

    <nav class="bg-[#2D3E50] text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="{{ route('beranda') }}" class="flex items-center space-x-3">
                <i class="bi bi-arrow-left-circle text-xl hover:text-yellow-400 transition"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Kembali ke Beranda</span>
            </a>
            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-yellow-400">Usulan Buku Baru</div>
        </div>
    </nav>

    <main class="container mx-auto py-12 px-6">
        <div class="max-w-2xl mx-auto">

            <div class="mb-10 text-center">
                <div class="inline-block p-4 rounded-3xl bg-blue-50 text-blue-600 mb-4">
                    <i class="bi bi-journal-plus text-3xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-[#1A2B3C] tracking-[0.1em] leading-snug uppercase">Usul Buku Perpustakaan ITH</h2>
                <p class="text-slate-500 text-sm mt-2">Bantu kami memperkaya koleksi literatur untuk menunjang perkuliahan.</p>
            </div>

            <div class="glass-card p-8 md:p-10 rounded-[2.5rem] shadow-xl">
                <form action="#" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Judul Buku yang Diusulkan</label>
                        <input type="text" name="judul" placeholder="Masukkan judul buku lengkap..."
                            class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-6 text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Penulis / Pengarang</label>
                            <input type="text" name="penulis" placeholder="Nama penulis..."
                                class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-6 text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Estimasi Tahun Terbit</label>
                            <input type="number" name="tahun" placeholder="Contoh: 2024"
                                class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-6 text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Alasan Pengusulan (Opsional)</label>
                        <textarea name="alasan" rows="4" placeholder="Contoh: Referensi utama mata kuliah Struktur Data..."
                            class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-6 text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-[#2D3E50] text-white py-4 rounded-2xl font-bold uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg flex items-center justify-center gap-3">
                        <i class="bi bi-send-fill"></i> Kirim Usulan
                    </button>
                </form>
            </div>

            <div class="mt-8 flex items-start gap-4 p-6 bg-yellow-50 rounded-3xl border border-yellow-100">
                <i class="bi bi-info-circle-fill text-yellow-600 mt-1"></i>
                <p class="text-[11px] text-yellow-800 leading-relaxed font-semibold uppercase tracking-wide">
                    Usulan Anda akan diverifikasi oleh tim pustakawan ITH. Anda akan menerima notifikasi jika buku telah tersedia di katalog.
                </p>
            </div>

        </div>
    </main>

</body>
</html>
