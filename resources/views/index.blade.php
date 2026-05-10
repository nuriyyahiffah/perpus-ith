@extends('master')

@section('content')
<!-- HERO SECTION DENGAN GRADASI (Sesuai image_3c072d.png) -->
<section class="relative bg-gradient-to-b from-[#DFE9F3] via-[#F8FAFC] to-white min-h-[550px] flex items-center px-8 md:px-16 overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute top-20 right-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-[100px]"></div>

    <div class="max-w-7xl mx-auto w-full grid grid-cols-1 md:grid-cols-2 items-center gap-12">
        
        <!-- Sisi Kiri: Pesan Utama -->
        <div class="z-10 animate-fade-in-up">
            <h1 class="text-[60px] md:text-[72px] font-black text-[#2D3E50] leading-[0.9] tracking-tighter">
                PERPUSTAKAAN <br> 
                <span class="text-indigo-700 italic relative">
                    ITH
                    <span class="absolute bottom-2 left-0 w-full h-2 bg-indigo-700/10 -z-10"></span>
                </span>
            </h1>
            
            <!-- Garis Dekorasi Tebal -->
            <div class="w-20 h-2 bg-indigo-700 my-10 rounded-full"></div>
            
            <p class="max-w-md text-slate-500 text-sm md:text-base italic font-medium leading-relaxed">
                "Temukan referensi terbaik yang telah dikurasi oleh dosen untuk mendukung perkuliahanmu di Kampus Teknologi."
            </p>
            
            <!-- Status Badge -->
            <div class="mt-10 flex items-center gap-3 bg-white/50 w-fit px-4 py-2 rounded-full border border-white shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-700">
                    Sistem Berjalan Normal
                </span>
            </div>
        </div>

        <!-- Sisi Kanan: Ilustrasi Visual -->
        <div class="hidden md:flex justify-end relative">
            <!-- Ilustrasi Rak Buku (Gunakan URL image yang valid) -->
            <div class="relative z-10 transform hover:scale-105 transition-transform duration-700">
                <img src="https://illustrations.popsy.co/amber/reading-a-book.svg" alt="Illustration" class="w-[450px]">
            </div>
            
            <!-- Floating Elements -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-400/10 rounded-full blur-3xl"></div>
        </div>
    </div>
</section>

<!-- SECTION LAYANAN RINGKAS -->
<section class="py-20 px-8 md:px-16 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-4 mb-12">
            <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em]">Informasi Utama</h2>
            <div class="flex-grow h-[1px] bg-slate-100"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Jam Layanan -->
            <div class="group p-8 rounded-[32px] bg-slate-50 border border-transparent hover:border-yellow-400 hover:bg-white hover:shadow-xl transition-all duration-500">
                <div class="w-12 h-12 bg-yellow-400/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-yellow-400 transition-colors">
                    <i class="bi bi-clock-fill text-yellow-600 group-hover:text-[#2D3E50]"></i>
                </div>
                <h3 class="font-extrabold text-[#2D3E50] text-lg mb-3">Jam Layanan</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">Melayani kunjungan fisik setiap Senin s/d Jumat pukul 08:00 - 16:00 WITA.</p>
            </div>

            <!-- Koleksi Digital -->
            <div class="group p-8 rounded-[32px] bg-slate-50 border border-transparent hover:border-indigo-600 hover:bg-white hover:shadow-xl transition-all duration-500">
                <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors">
                    <i class="bi bi-journal-bookmark-fill text-indigo-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-extrabold text-[#2D3E50] text-lg mb-3">Akses Mandiri</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">Mahasiswa dapat melakukan cek ketersediaan buku secara mandiri melalui portal.</p>
            </div>

            <!-- Bebas Pustaka -->
            <div class="group p-8 rounded-[32px] bg-slate-50 border border-transparent hover:border-emerald-500 hover:bg-white hover:shadow-xl transition-all duration-500">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-500 transition-colors">
                    <i class="bi bi-patch-check-fill text-emerald-500 group-hover:text-white"></i>
                </div>
                <h3 class="font-extrabold text-[#2D3E50] text-lg mb-3">Bebas Pustaka</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">Layanan administrasi bebas pinjaman buku untuk syarat Yudisium dan Wisuda.</p>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 1s ease-out;
    }
</style>
@endsection