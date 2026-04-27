<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <title>Daftar Akun - Digital Library ITH</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top left, #A7C5E0 0%, #C9D9E8 40%, #F8FAFC 100%);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(45, 62, 80, 0.15);
        }

        .input-focus:focus {
            border-color: #2D3E50;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(45, 62, 80, 0.05);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md glass-card p-10 rounded-[3rem] relative overflow-hidden">

        <div class="mb-8 text-center relative z-10">
            <div class="flex justify-center mb-5">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-14 drop-shadow-sm">
            </div>
            <h2 class="text-2xl font-extrabold text-[#2D3E50] uppercase tracking-tighter italic leading-none">
                Registrasi <span class="text-blue-600">Akun Baru</span>
            </h2>
            <div class="h-1 w-12 bg-yellow-400 mx-auto mt-2 rounded-full"></div>
            <p class="text-slate-500 text-[11px] mt-3 tracking-wide font-semibold">
                Gunakan email resmi <span class="text-[#2D3E50] font-bold">@ith.ac.id</span>
            </p>
        </div>

        @if(session('regError'))
        <div class="bg-red-500 text-white p-4 rounded-2xl mb-6 text-[10px] font-bold uppercase tracking-wider flex items-center shadow-lg">
            <i class="bi bi-exclamation-triangle-fill mr-3 text-lg"></i>
            {{ session('regError') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 rounded-2xl mb-6 text-[10px] font-bold">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4 relative z-10">
            @csrf

            <div class="space-y-1">
                <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-[0.15em] ml-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Dr. Budi Santoso, M.T."
                    class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-5 text-sm outline-none transition-all input-focus text-slate-700" required>
            </div>

            <div class="space-y-1">
    <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-[0.15em] ml-2">Email Kampus</label>
    <input type="email" id="email_input" name="email" value="{{ old('email') }}"
        placeholder="mahasiswa: nim@ith.ac.id | dosen: nama.lengkap@ith.ac.id"
        class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-5 text-sm outline-none transition-all input-focus text-slate-700" required>
            </div>

            <div class="space-y-1" id="identity_container">
                <label id="identity_label" class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-[0.15em] ml-2">NIM / NIP</label>
                <input type="text" id="identity_input" name="nomor_identitas" value="{{ old('nomor_identitas') }}" placeholder="Masukkan Nomor Identitas"
                    class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-5 text-sm outline-none transition-all input-focus text-slate-700" required>
            </div>

            <div class="space-y-1" id="prodi_container">
                <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-[0.15em] ml-2">Program Studi</label>
                <div class="relative">
                    <select name="prodi" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-5 text-sm outline-none transition-all input-focus text-slate-700 appearance-none cursor-pointer shadow-sm">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->nama_kategori }}" {{ old('prodi') == $cat->nama_kategori ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down absolute right-5 top-3.5 text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-[0.15em] ml-2">Nomor WhatsApp</label>
                <div class="relative">
                    <span class="absolute left-5 top-3 text-sm text-slate-400 font-bold">+62</span>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="812345678"
                        class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-14 pr-5 text-sm outline-none transition-all input-focus text-slate-700" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-[0.15em] ml-2">Password</label>
                    <input type="password" name="password" placeholder="••••••"
                        class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-5 text-sm outline-none transition-all input-focus shadow-sm" required>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-[0.15em] ml-2">Konfirmasi</label>
                    <input type="password" name="password_confirmation" placeholder="••••••"
                        class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-5 text-sm outline-none transition-all input-focus shadow-sm" required>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-[#FFD666] hover:bg-[#FFC107] text-[#2D3E50] font-black py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-yellow-200/50 mt-4 uppercase tracking-[0.2em] text-[11px] active:scale-95 border-b-4 border-yellow-600">
                Daftar Sekarang
            </button>

            <p class="text-center text-[11px] text-slate-500 mt-6 font-medium">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-700 font-extrabold hover:text-blue-800 ml-1 underline decoration-2 underline-offset-4">LOGIN DISINI</a>
            </p>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email_input');
            const prodiContainer = document.getElementById('prodi_container');
            const identityLabel = document.getElementById('identity_label');
            const identityInput = document.getElementById('identity_input');

            function updateFormLayout() {
                const emailValue = emailInput.value.toLowerCase();
                const username = emailValue.split('@')[0];

                // 1. Logika Toggle Kolom Prodi (Sembunyikan untuk Admin/Pustakawan)
                if (emailValue.includes('admin') || emailValue.includes('library') || emailValue.includes('pustakawan')) {
                    prodiContainer.style.display = 'none';
                } else {
                    prodiContainer.style.display = 'block';
                }

                // 2. Logika Perubahan Label NIM/NIP
                if (username === '') {
                    identityLabel.innerText = 'NIM / NIP';
                    identityInput.placeholder = 'Masukkan Nomor Identitas';
                } else if (!isNaN(username) && username.length > 0) {
                    // Jika username adalah angka, maka Mahasiswa
                    identityLabel.innerText = 'Nomor Induk Mahasiswa (NIM)';
                    identityInput.placeholder = 'Contoh: 230204111';
                } else {
                    // Jika username mengandung huruf, maka Dosen/Staf
                    identityLabel.innerText = 'Nomor Induk Pegawai (NIP)';
                    identityInput.placeholder = 'Contoh: 19880101XXXXXXXX';
                }
            }

            // Event listener saat user mengetik email
            emailInput.addEventListener('input', updateFormLayout);

            // Jalankan saat pertama kali dimuat (untuk old input)
            updateFormLayout();
        });
    </script>

</body>
</html>
