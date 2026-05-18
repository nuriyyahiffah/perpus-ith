<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klaim Buku Prodi - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        /* Custom Checkbox Size */
        .custom-checkbox { width: 28px; height: 28px; cursor: pointer; transition: all 0.2s; }
    </style>
</head>
<body class="antialiased">

    {{-- Navbar Seragam Sesuai Referensi Gambar --}}
    <nav class="bg-[#2D3E50] text-white py-4 px-6 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">

            {{-- Sisi Kiri: Tombol Kembali, Logo, dan Nama Instansi --}}
            <div class="flex items-center space-x-5">
                {{-- Tombol Kembali mengarah ke Beranda Dosen --}}
                <a href="{{ route('dosen.beranda') }}" class="text-white hover:text-slate-300 transition text-xl flex items-center">
                    <i class="bi bi-arrow-left text-2xl font-bold"></i>
                </a>

                {{-- Garis Pembatas Vertikal Pertama --}}
                <div class="h-8 w-[1px] bg-slate-500/40"></div>

                {{-- Logo dan Teks Instansi Perpustakaan --}}
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo_ith.png') }}" alt="Logo" class="h-9">

                    {{-- Garis Pembatas Vertikal Kedua --}}
                    <div class="h-8 w-[1px] bg-slate-500/40 mx-1"></div>

                    <div class="flex flex-col">
                        <span class="text-xs font-black uppercase tracking-wider leading-none">PERPUSTAKAAN</span>
                        <span class="text-[8px] text-yellow-400 font-bold uppercase tracking-wider mt-1">Institut Teknologi Bacharuddin Jusuf Habibie</span>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Informasi Pengguna Aktif --}}
            <div class="flex flex-col text-right">
                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Pengguna Aktif</span>
                <span class="text-xs font-bold text-white tracking-wide leading-none">{{ Auth::user()->name }}</span>
            </div>

        </div>
    </nav>

    <main class="py-12 px-6">
        <div class="max-w-5xl mx-auto">

            {{-- Header & Info --}}
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <h1 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tight">Klaim Buku Prodi</h1>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Otoritas Kaprodi: <span class="text-blue-600">{{ Auth::user()->prodi }}</span></p>
                </div>

                {{-- Navigasi Tab --}}
                <div class="inline-flex bg-slate-200/50 p-1 rounded-2xl shadow-inner border border-slate-200">
                    <a href="?tab=semua" class="px-6 py-2 text-[10px] font-black uppercase rounded-xl transition-all {{ $tab == 'semua' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Katalog</a>
                    <a href="?tab=prodi" class="px-6 py-2 text-[10px] font-black uppercase rounded-xl transition-all {{ $tab == 'prodi' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Buku Prodi</a>
                    <a href="?tab=belum" class="px-6 py-2 text-[10px] font-black uppercase rounded-xl transition-all {{ $tab == 'belum' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Belum Dicentang</a>
                </div>
            </div>

            {{-- Tabel Dua Sisi --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden transition-all duration-300">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Buku</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-40">Klaim (Centang)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($bukus as $buku)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            {{-- Sisi Kiri: Info Buku --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-md transition-all">
                                        <i class="bi bi-journal-bookmark-fill text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-[#2D3E50] text-sm uppercase leading-tight group-hover:text-blue-600 transition-colors">{{ $buku->judul }}</h3>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide italic">{{ $buku->penulis }}</span>
                                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                            <span class="text-[9px] font-black text-blue-400 uppercase">{{ $buku->tahun }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Sisi Kanan: Centang Otomatis --}}
                            <td class="px-8 py-6 text-center">
                                <div class="flex justify-center items-center">
                                    <input type="checkbox"
                                           class="custom-checkbox rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all checked:bg-blue-600 shadow-sm"
                                           onchange="toggleClaim(this, {{ $buku->id }})"
                                           {{ in_array($buku->id, $claimedIds) ? 'checked' : '' }}>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-8 py-20 text-center">
                                <i class="bi bi-search text-4xl text-slate-200 mb-4 block"></i>
                                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Data buku tidak ditemukan dalam kategori ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-10 flex justify-center">
                {{ $bukus->appends(['tab' => $tab])->links() }}
            </div>

        </div>
    </main>

    {{-- Script AJAX Logika Centang Otomatis --}}
    <script>
        function toggleClaim(checkbox, bukuId) {
            // Efek visual saat proses simpan
            checkbox.style.opacity = '0.3';
            checkbox.disabled = true;

            fetch("{{ route('dosen.claim.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ buku_id: bukuId })
            })
            .then(response => response.json())
            .then(data => {
                checkbox.style.opacity = '1';
                checkbox.disabled = false;
                console.log('Status:', data.action);

                // Opsional: Jika di Tab "Belum Dicentang", baris bisa langsung hilang
                @if($tab == 'belum')
                    if(data.action === 'added') checkbox.closest('tr').remove();
                @endif
            })
            .catch(error => {
                checkbox.style.opacity = '1';
                checkbox.disabled = false;
                checkbox.checked = !checkbox.checked; // Balikkan posisi jika gagal
                alert('Gagal menyimpan perubahan. Periksa koneksi atau sesi login Anda.');
            });
        }
    </script>

</body>
</html>
