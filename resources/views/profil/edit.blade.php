<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Digital Library ITH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        .form-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02); }
        input:focus, select:focus, textarea:focus { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1); border-color: #3b82f6 !important; }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen flex">
        <main class="flex-1 py-12 px-6 md:px-12">
            <div class="max-w-4xl mx-auto">

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-2 h-8 bg-yellow-400 rounded-full"></div>
                            <h1 class="text-4xl font-extrabold text-[#2D3E50] tracking-tight">
                                Pengaturan <span class="text-blue-600">Profil</span>
                            </h1>
                        </div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] ml-5">
                            Identitas Digital {{ Auth::user()->role }} ITH
                        </p>
                    </div>
                    <a href="@if(Auth::user()->role === 'mahasiswa'){{ route('mahasiswa.beranda') }}@elseif(Auth::user()->role === 'dosen'){{ route('dosen.beranda') }}@else{{ url('/') }}@endif" class="flex items-center text-xs font-bold text-slate-500 hover:text-[#2D3E50] transition">
                        <i class="bi bi-arrow-left me-2"></i> KEMBALI KE BERANDA
                    </a>
                </div>

                {{-- NOTIFIKASI SUKSES --}}
                @if(session('success'))
                <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl mb-8 flex items-center shadow-lg shadow-emerald-200 animate-bounce">
                    <i class="bi bi-check-circle-fill text-2xl me-3"></i>
                    <span class="text-sm font-bold uppercase tracking-wider">{{ session('success') }}</span>
                </div>
                @endif

                {{-- NOTIFIKASI ERROR VALIDASI --}}
                @if ($errors->any())
                <div class="bg-rose-500 text-white px-6 py-4 rounded-2xl mb-8 shadow-lg shadow-rose-200">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill text-xl me-2"></i>
                        <span class="text-sm font-bold uppercase">Terjadi Kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-[11px] font-semibold uppercase tracking-wide opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white rounded-[3rem] overflow-hidden border border-slate-100 form-shadow">
                    <div class="md:flex">

                        <div class="md:w-1/3 bg-[#2D3E50] p-10 text-white flex flex-col justify-between">
                            <div>
                                <div class="w-16 h-16 bg-yellow-400 rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-lg">
                                    <i class="bi bi-person-vcard text-[#2D3E50]"></i>
                                </div>
                                <h3 class="text-xl font-black uppercase leading-tight mb-2">Informasi Akun</h3>
                                <p class="text-[10px] text-slate-400 font-bold leading-relaxed uppercase italic">
                                    Lengkapi nomor WhatsApp Anda untuk menerima notifikasi otomatis terkait peminjaman buku secara real-time.
                                </p>
                            </div>
                            <div class="text-[9px] font-bold text-slate-500 tracking-widest uppercase italic leading-loose">
                                Digital Library ITH <br> Sistem Informasi Perpustakaan
                            </div>
                        </div>

                        <div class="md:w-2/3 p-8 md:p-14">
                            <form action="{{ route('profil.update') }}" method="POST" class="space-y-8">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4 pb-6 border-b border-slate-100">
                                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Identitas Resmi</h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                                            <div class="px-5 py-3 bg-slate-100 rounded-xl text-slate-400 font-bold text-sm border border-slate-200 cursor-not-allowed italic">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">
                                                {{ Auth::user()->role == 'mahasiswa' ? 'NIM' : 'NIP' }}
                                            </label>
                                            <div class="px-5 py-3 bg-slate-100 rounded-xl text-slate-400 font-bold text-sm border border-slate-200 cursor-not-allowed italic">
                                                {{ $user->nomor_identitas ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Program Studi / Unit</label>
                                        <div class="px-5 py-3 bg-slate-100 rounded-xl text-slate-400 font-bold text-sm border border-slate-200 cursor-not-allowed italic">
                                            {{ $user->prodi ?? 'Umum' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6 pt-2">
                                    <h4 class="text-[11px] font-black text-blue-600 uppercase tracking-widest mb-4">Kontak Notifikasi</h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Email Institusi</label>
                                            <div class="px-5 py-3 bg-blue-50/50 rounded-xl text-blue-700 font-bold text-sm border border-blue-100 cursor-not-allowed italic">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Pribadi</label>
                                            <div class="relative group">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                                    <i class="bi bi-envelope-at"></i>
                                                </div>
                                                <input type="email" name="email_pribadi" value="{{ old('email_pribadi', $user->email_pribadi) }}" placeholder="contoh@gmail.com" class="w-full bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block pl-11 p-4 transition-all">
                                            </div>
                                        </div>
                                        <div class="space-y-2 md:col-span-2">
                                            <label class="text-[10px] font-black text-[#2D3E50] uppercase tracking-widest ml-1">Nomor WhatsApp (Aktif)</label>
                                            <div class="relative group">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                                    <i class="bi bi-whatsapp"></i>
                                                </div>
                                                <input type="text" name="no_telp" inputmode="numeric" value="{{ old('no_telp', $user->no_telp) }}" class="w-full pl-11 pr-5 py-4 bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 block transition-all" placeholder="Contoh: 08123456789">
                                            </div>
                                            <p class="text-[9px] text-slate-400 font-bold italic ml-1">* Pastikan nomor aktif untuk menerima notifikasi pengembalian buku.</p>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Alamat Tinggal / Domisili</label>
                                        <textarea name="alamat" rows="2" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block transition-all" placeholder="Masukkan alamat lengkap Anda...">{{ old('alamat', $user->alamat) }}</textarea>
                                    </div>
                                </div>

                                {{-- Section Ganti Password --}}
                                <div class="space-y-6 pt-6 border-t border-slate-100">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-[11px] font-black text-rose-500 uppercase tracking-widest italic">Keamanan Akun</h4>
                                        <div class="h-[1px] flex-1 bg-rose-100"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Password Baru --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Password Baru</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-500">
                <i class="bi bi-shield-lock"></i>
            </div>
            <input type="password"
                   name="password"
                   autocomplete="new-password"
                   class="w-full bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 block pl-11 p-4 transition-all"
                   placeholder="Kosongkan jika tidak diubah">
        </div>
    </div>

    {{-- Konfirmasi Password --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Konfirmasi Password</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-500">
                <i class="bi bi-shield-check"></i>
            </div>
            <input type="password"
                   name="password_confirmation"
                   autocomplete="new-password"
                   class="w-full bg-slate-50 border border-slate-200 text-[#2D3E50] text-xs font-bold rounded-2xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 block pl-11 p-4 transition-all"
                   placeholder="Ulangi password baru">
        </div>
    </div>
</div>

                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full py-4 bg-yellow-400 hover:bg-yellow-500 text-[#2D3E50] font-black uppercase tracking-[0.2em] text-xs rounded-2xl shadow-xl transition-all border-b-4 border-yellow-600 active:translate-y-1 active:border-b-0">
                                        <i class="bi bi-save2 me-2"></i> Simpan Perubahan Profil
                                    </button>
                                </div>
                            </form>

                            <div class="mt-8 text-center border-t border-slate-50 pt-6">
                                <a href="https://wa.me/628XXXXXXXXXX?text=Halo%20Admin%20SIPUSTAKA,%20saya%20lupa%20password%20dengan%20Identitas:%20{{ $user->nomor_identitas }}"
                                   target="_blank" class="text-[10px] font-black text-slate-400 hover:text-blue-600 transition uppercase tracking-[0.2em]">
                                    Lupa Password? <span class="text-blue-500">Hubungi Admin via WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="mt-12 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.5em]">&copy; 2026 Institut Teknologi Bacharuddin Jusuf Habibie</p>
                </footer>

            </div>
        </main>
    </div>

    <script>
        // Validasi nomor WhatsApp - hanya angka yang dibolehkan
        const noTelpInput = document.querySelector('input[name="no_telp"]');

        if (noTelpInput) {
            noTelpInput.addEventListener('input', function(e) {
                // Simpan posisi cursor
                const cursorPos = this.selectionStart;

                // Ambil nilai asli
                const originalValue = this.value;

                // Hapus semua karakter yang bukan angka
                this.value = this.value.replace(/[^0-9]/g, '');

                // Jika ada perubahan (ada karakter non-angka), tampilkan notifikasi
                if (originalValue !== this.value && originalValue.length > 0) {
                    showNotification('Hanya angka yang dibolehkan!', 'error');
                }

                // Restore posisi cursor
                this.selectionStart = this.selectionEnd = cursorPos - 1;
            });

            // Validasi on blur untuk pesan lebih detail
            noTelpInput.addEventListener('blur', function() {
                if (this.value && !/^\d{9,15}$/.test(this.value)) {
                    showNotification('Nomor WhatsApp harus 9-15 digit angka', 'error');
                } else if (this.value) {
                    showNotification('Nomor WhatsApp valid', 'success');
                }
            });
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Hapus notifikasi sebelumnya jika ada
            const existingNotif = document.getElementById('validation-notification');
            if (existingNotif) {
                existingNotif.remove();
            }

            // Buat elemen notifikasi baru
            const notification = document.createElement('div');
            notification.id = 'validation-notification';

            const bgColor = type === 'error' ? 'bg-rose-500' : 'bg-emerald-500';
            const icon = type === 'error' ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill';

            notification.innerHTML = `
                <div class="${bgColor} text-white px-6 py-4 rounded-2xl flex items-center shadow-lg fixed top-6 right-6 z-50 animate-bounce" style="max-width: 350px;">
                    <i class="bi ${icon} text-xl me-3"></i>
                    <span class="text-sm font-bold uppercase tracking-wider">${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Hapus notifikasi setelah 3 detik
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>

</body>
</html>
