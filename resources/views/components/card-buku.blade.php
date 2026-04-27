@props(['buku'])

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
    <div class="relative aspect-[3/4] overflow-hidden">
        <img src="{{ asset('images/' . $buku->gambar_buku) }}"
             alt="{{ $buku->judul }}"
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

        <div class="absolute top-4 left-4">
            <span class="px-3 py-1 bg-white/90 backdrop-blur shadow-sm rounded-full text-[10px] font-black text-blue-600 uppercase tracking-tighter">
                {{ $buku->kategori->nama_kategori ?? 'Umum' }}
            </span>
        </div>
    </div>

    <div class="p-6">
        <h4 class="font-bold text-[#2D3E50] text-lg leading-tight mb-1 line-clamp-2 min-h-[3rem]">
            {{ $buku->judul }}
        </h4>
        <p class="text-slate-400 text-xs font-medium mb-4 italic">
            by {{ $buku->penulis }}
        </p>

        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
            <div class="flex flex-col">
                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Stok</span>
                <span class="text-sm font-bold {{ $buku->stok > 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $buku->stok }} Tersedia
                </span>
            </div>

            <a href="#" class="p-3 bg-slate-50 text-[#2D3E50] rounded-xl group-hover:bg-yellow-400 group-hover:text-[#2D3E50] transition-colors">
                <i class="bi bi-arrow-right-short text-xl"></i>
            </a>
        </div>
    </div>
</div>
