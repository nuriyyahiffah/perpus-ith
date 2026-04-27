<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Referensi Prodi - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        [x-cloak] { display: none !important; }

        /* Style Khusus Cetak */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .print-container { box-shadow: none !important; border: none !important; width: 100% !important; max-width: 100% !important; margin: 0 !important; }
            .print-table { border: 1px solid #000 !important; }
            .print-table th, .print-table td { border: 1px solid #000 !important; color: black !important; }
            .signature-area { display: block !important; margin-top: 50px; }
        }
        .signature-area { display: none; }
    </style>
</head>
<body class="antialiased" x-data="{ profileOpen: false }">

    {{-- NAVBAR --}}
    <nav class="bg-[#2D3E50] text-white p-4 sticky top-0 z-50 shadow-md no-print">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-10">
                <span class="text-[10px] font-bold leading-tight uppercase tracking-wider">
                    Digital<br><span class="text-yellow-400">Library ITH</span>
                </span>
            </div>

            <div class="flex items-center space-x-6">
                <a href="{{ route('dosen.claim.index') }}" class="text-[10px] font-bold uppercase hover:text-yellow-400 transition flex items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Klaim
                </a>
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i class="bi bi-printer-fill"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-10 px-6 print-container">
        
        {{-- KOP SURAT (Hanya muncul saat diprint) --}}
        <div class="hidden print:flex items-center justify-center gap-5 border-b-4 border-black pb-4 mb-8 text-center">
            <img src="{{ asset('images/logo_ith.png') }}" class="h-20">
            <div>
                <h1 class="text-xl font-bold uppercase">Institut Teknologi Bacharuddin Jusuf Habibie</h1>
                <p class="text-sm italic">Jl. Balai Kota No. 1, Parepare, Sulawesi Selatan</p>
                <p class="text-xs font-bold uppercase mt-1 text-indigo-600">Daftar Koleksi Referensi Program Studi: {{ Auth::user()->prodi }}</p>
            </div>
        </div>

        {{-- HEADER HALAMAN (Screen Only) --}}
        <div class="mb-10 no-print flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-extrabold text-[#1A2B3C] uppercase tracking-tighter italic">Referensi <span class="text-indigo-600">Buku Prodi</span></h2>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1 italic">Koleksi perpustakaan yang ditandai oleh Program Studi {{ Auth::user()->prodi }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Periode Laporan</p>
                <p class="text-xs font-bold text-slate-800 uppercase">{{ date('d F Y') }}</p>
            </div>
        </div>

        {{-- TABEL LAPORAN --}}
        <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden print:border-none print:shadow-none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print-table">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 print:bg-gray-100">
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest print:text-black print:text-[12px]">No</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest print:text-black print:text-[12px]">Judul Buku</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest print:text-black print:text-[12px]">Penulis & Tahun</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center print:text-black print:text-[12px]">Status Referensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($riwayatKlaim as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6 text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-800 leading-tight uppercase tracking-tight">{{ $item->buku->judul }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[10px] text-indigo-600 font-bold uppercase italic">{{ $item->buku->penulis }}</p>
                                <p class="text-[10px] text-slate-400 font-bold tracking-widest mt-0.5">{{ $item->buku->tahun }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100 print:border-none">
                                    {{ $item->status == 'disetujui' ? 'Referensi Tetap' : $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <i class="bi bi-inbox text-4xl text-slate-200 mb-3 block"></i>
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest italic">Belum ada buku referensi yang tercatat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- AREA TANDA TANGAN (Hanya muncul saat diprint) --}}
        <div class="signature-area hidden print:grid grid-cols-2 gap-20 text-center">
            <div class="mt-10">
                <p class="text-sm">Mengetahui,</p>
                <p class="text-sm font-bold uppercase mb-20">Kepala Perpustakaan ITH</p>
                <p class="text-sm font-bold underline">__________________________</p>
                <p class="text-xs font-medium">NIP. .....................................</p>
            </div>
            <div class="mt-10">
                <p class="text-sm">Parepare, {{ date('d M Y') }}</p>
                <p class="text-sm font-bold uppercase mb-20">Ketua Program Studi {{ Auth::user()->prodi }}</p>
                <p class="text-sm font-bold underline">{{ Auth::user()->name }}</p>
                <p class="text-xs font-medium">NIP. {{ Auth::user()->nomor_identitas }}</p>
            </div>
        </div>

    </div>

</body>
</html>