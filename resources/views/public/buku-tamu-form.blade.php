<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - PERPUSTAKAAN ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* Gradasi Baby Blue yang lembut */
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
        }
        .glass-card { 
            background: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="max-w-xl w-full">
        <div class="text-center mb-10">
            <div class="inline-flex w-20 h-20 bg-blue-500 rounded-[2rem] items-center justify-center shadow-xl shadow-blue-200 mb-6 rotate-3 hover:rotate-0 transition-transform duration-500">
                <i class="bi bi-book-half text-white text-4xl"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Buku Tamu Digital</h1>
            <p class="text-blue-500 font-bold mt-2 uppercase text-[10px] tracking-[0.3em]">Perpustakaan ITH Parepare</p>
        </div>

        @if(session('success'))
            <div class="bg-white border border-emerald-100 text-emerald-600 p-6 rounded-[2.5rem] mb-8 flex items-center gap-4 shadow-sm animate-in fade-in zoom-in duration-500">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center shrink-0">
                    <i class="bi bi-check2-circle text-2xl"></i>
                </div>
                <div>
                    <p class="font-black uppercase text-[11px] tracking-widest">Berhasil Dicatat!</p>
                    <p class="text-xs opacity-80 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="glass-card p-8 md:p-10 rounded-[3.5rem] shadow-2xl shadow-blue-100 relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-400/10 blur-[60px]"></div>

            <form action="{{ route('buku-tamu.store') }}" method="POST" class="relative z-10 space-y-6">
                @csrf
                
                <div>
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-4 mb-2 block">Nama Lengkap</label>
                    <div class="relative group">
                        <i class="bi bi-person absolute left-5 top-4 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" name="nama" required placeholder="Andi Wijaya" 
                            class="w-full bg-white/50 border border-slate-100 rounded-2xl px-12 py-4 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 transition-all placeholder:text-slate-300 text-slate-700">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-4 mb-2 block">NIM / NIDN</label>
                        <input type="text" name="identitas" required placeholder="Masukkan ID" 
                            class="w-full bg-white/50 border border-slate-100 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 transition-all text-slate-700">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-4 mb-2 block">Status Pengunjung</label>
                        <div class="relative">
                            <select name="status_pengunjung" required class="w-full bg-white/50 border border-slate-100 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-400 transition-all appearance-none cursor-pointer text-slate-700">
                                <option value="" disabled selected>Pilih...</option>
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Tendik">Tendik</option>
                                <option value="Umum">Umum</option>
                            </select>
                            <i class="bi bi-chevron-down absolute right-6 top-4 text-slate-300 pointer-events-none text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-4 mb-2 block">Instansi / Program Studi</label>
                    <input type="text" name="instansi_prodi" placeholder="Contoh: Sistem Informasi" 
                        class="w-full bg-white/50 border border-slate-100 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-400 transition-all text-slate-700">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-4 mb-2 block">Tujuan Kunjungan</label>
                    <textarea name="keperluan" required rows="3" placeholder="Apa tujuan kunjungan Anda?" 
                        class="w-full bg-white/50 border border-slate-100 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-400 transition-all resize-none text-slate-700"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-black py-5 rounded-2xl shadow-lg shadow-blue-200 transition-all uppercase tracking-[0.2em] text-xs flex items-center justify-center gap-3 active:scale-[0.98]">
                        Simpan Kehadiran
                        <i class="bi bi-send-fill text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-12 text-center">
             <div class="text-[9px] font-black uppercase tracking-widest text-blue-300 italic">PERPUSTAKAAN ITH &bull; 2026</div>
        </div>
    </div>

</body>
</html>