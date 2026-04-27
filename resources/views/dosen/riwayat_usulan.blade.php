<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Usulan Buku - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased">

    {{-- Navbar --}}
    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-8">
                <span class="text-[10px] font-bold uppercase tracking-wider">Digital <span class="text-yellow-400">Library ITH</span></span>
            </div>
            <a href="{{ route('dosen.beranda') }}" class="text-[10px] font-bold uppercase hover:text-yellow-400 transition">Kembali</a>
        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="mb-10">
                <h1 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tight">Riwayat Usulan Buku</h1>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Daftar usulan buku yang telah Anda ajukan</p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @forelse($riwayatUsulan as $usulan)
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 hover:shadow-lg transition-all">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                            <i class="bi bi-send-check text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#2D3E50] text-sm uppercase">{{ $usulan->judul_buku }}</h3>
                            <p class="text-[10px] text-slate-400 mt-1">Penulis: {{ $usulan->penulis }} | Tahun: {{ $usulan->tahun_terbit }}</p>
                            <p class="text-[9px] font-bold text-blue-600 mt-2 uppercase tracking-wider">
                                <i class="bi bi-calendar3 mr-1"></i> Diajukan: {{ $usulan->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        @if($usulan->status == 'disetujui' || $usulan->status == 'diterima')
                            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                                <i class="bi bi-check-circle-fill mr-1"></i> Disetujui
                            </span>
                        @elseif($usulan->status == 'ditolak')
                            <span class="px-4 py-1.5 bg-red-100 text-red-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                                <i class="bi bi-x-circle-fill mr-1"></i> Ditolak
                            </span>
                        @else
                            <span class="px-4 py-1.5 bg-amber-100 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                                <i class="bi bi-clock-history mr-1"></i> Menunggu
                            </span>
                        @endif
                        
                        @if($usulan->keterangan)
                        <p class="text-[9px] text-slate-400 italic max-w-[200px] text-right">"{{ $usulan->keterangan }}"</p>
                        @endif
                    </div>
                </div>
                @empty
                {{-- Tampilan jika data kosong --}}
                <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-folder2-open text-3xl text-slate-300"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Belum ada riwayat usulan</p>
                    <a href="{{ route('dosen.usulan.create') }}" class="mt-4 inline-block text-[10px] font-black text-blue-600 uppercase border-b-2 border-blue-600 pb-1 hover:text-blue-800 transition">Ajukan Usulan Sekarang</a>
                </div>
                @endforelse
            </div>
        </div>
    </main>

</body>
</html>