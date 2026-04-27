<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Klaim Buku - ITH Lib</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8FAFC] p-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase italic">Riwayat <span class="text-emerald-600">Klaim Buku</span></h1>
                <p class="text-slate-500 text-sm italic">Daftar buku yang telah Anda klaim dari katalog.</p>
            </div>
            <a href="{{ route('beranda') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest flex items-center gap-2 transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Info Buku</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Tanggal Klaim</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatKlaim as $k)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-14 bg-slate-200 rounded-lg flex-shrink-0 overflow-hidden shadow-sm">
                                    <img src="{{ asset('storage/' . $k->buku->cover) }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-700 text-sm leading-tight">{{ $k->buku->judul }}</div>
                                    <div class="text-[10px] text-emerald-600 uppercase font-black tracking-wider mt-1">{{ $k->buku->kode_buku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="text-xs font-bold text-slate-400">{{ $k->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="p-6 text-center">
                            @if($k->status == 'diambil')
                                <span class="bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tight">
                                    <i class="bi bi-check-circle-fill me-1"></i> Sudah Diambil
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tight">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Pengambilan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-24 text-center">
                            <i class="bi bi-journal-x text-4xl text-slate-200 mb-4 block"></i>
                            <p class="text-slate-400 italic text-sm font-medium uppercase tracking-widest">Belum ada riwayat klaim buku.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
