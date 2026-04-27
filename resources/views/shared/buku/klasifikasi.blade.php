<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klasifikasi Buku - SIPUSTAKA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-[#F1F5F9] antialiased">
    @if(Auth::user()->role == 'admin')
        @include('layouts.partials.sidebar-admin')
    @else
        @include('layouts.partials.sidebar-pustakawan')
    @endif

    <div class="flex flex-col lg:pl-72 w-full min-h-screen">
        <main class="flex-1 p-8 lg:p-12">
            <header class="mb-10">
                <h1 class="text-3xl font-[900] text-slate-800 uppercase italic tracking-tighter">
                    Klasifikasi <span class="text-blue-600">Buku</span>
                </h1>
                <p class="text-slate-500 text-sm mt-2 font-semibold italic">Menampilkan koleksi berdasarkan kode klasifikasi: <span class="text-blue-600">{{ $kode }}</span></p>
            </header>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">No</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Judul Buku</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Penulis</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($buku as $index => $b)
                        <tr class="hover:bg-slate-50">
                            <td class="px-8 py-6 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-8 py-6 font-bold text-slate-700 text-sm uppercase">{{ $b->judul }}</td>
                            <td class="px-8 py-6 text-slate-500 text-sm italic">{{ $b->penulis }}</td>
                            <td class="px-8 py-6 text-center">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg font-black text-[10px]">{{ $b->stok }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400 italic font-bold uppercase text-xs tracking-widest">Tidak ada buku dalam klasifikasi ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
