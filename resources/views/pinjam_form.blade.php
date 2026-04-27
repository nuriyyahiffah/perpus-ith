<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Formulir Peminjaman - ITH</title>
</head>
<body class="bg-[#0F111A] flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-lg bg-[#1A1C2E] p-10 rounded-[2.5rem] shadow-2xl border border-white/5 relative">

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-black text-[#D4AF37] uppercase italic">Peminjaman</h2>
            <a href="{{ route('beranda') }}" class="bg-[#D4AF37]/10 text-[#D4AF37] text-[10px] px-4 py-2 rounded-full border border-[#D4AF37]/30 font-bold uppercase hover:bg-[#D4AF37]/20 transition">
                🔍 Katalog
            </a>
        </div>

        <form action="{{ route('pinjam.ajukan') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="buku_id" value="{{ $buku->id }}">

            <div class="bg-[#0F111A] p-4 rounded-2xl flex gap-4 border border-white/5">
                <div class="w-12 h-16 bg-gray-800 rounded shadow-md overflow-hidden">
                    <img src="{{ asset('images/' . $buku->gambar_buku) }}" class="w-full h-full object-cover">
                </div>
                <div class="text-white">
                    <h3 class="text-sm font-bold">{{ $buku->judul }}</h3>
                    <p class="text-[10px] text-[#D4AF37] font-bold uppercase opacity-70">{{ $buku->kategori }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-white">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Durasi (Hari)</label>
                    <input type="number" name="durasi" min="1" max="14" value="3"
                        class="w-full bg-[#0F111A] border border-white/10 rounded-xl py-3 px-4 text-[#D4AF37] font-bold outline-none focus:ring-1 focus:ring-[#D4AF37]">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Tgl Ambil</label>
                    <input type="date" name="tgl_pinjam" value="{{ date('Y-m-d') }}"
                        class="w-full bg-[#0F111A] border border-white/10 rounded-xl py-3 px-4 text-gray-400 text-sm outline-none">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#D4AF37] text-[#1A1C2E] font-black py-4 rounded-xl hover:bg-[#F9D71C] transition-all uppercase tracking-widest text-xs shadow-lg active:scale-95">
                Kirim Permintaan
            </button>
        </form>
    </div>

</body>
</html>
