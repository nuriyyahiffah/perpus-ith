<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <title>Login - Perpustakaan ITH</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Gradasi Biru yang kuat sesuai permintaan sebelumnya */
            background: linear-gradient(180deg, #A7C5E0 0%, #D8E5F0 50%, #F8FAFC 100%);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            box-shadow: 0 25px 50px -12px rgba(45, 62, 80, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .custom-input {
            background-color: #ffffff !important;
            border: 1.5px solid #E2E8F0;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            border-color: #2D3E50;
            box-shadow: 0 0 0 4px rgba(45, 62, 80, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md glass-card p-10 rounded-[3.5rem] relative overflow-hidden">
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-blue-200/40 blur-3xl rounded-full"></div>

        <div class="mb-10 text-center relative z-10">
            <div class="flex justify-center mb-5">
                <img src="{{ asset('images/logo_ith.png') }}" alt="Logo ITH" class="h-16 drop-shadow-sm">
            </div>
            <h2 class="text-2xl font-black text-[#2D3E50] uppercase tracking-tighter italic">
                LOGIN <span class="text-blue-600">PERPUS ITH</span>
            </h2>
            <div class="h-1 w-10 bg-yellow-400 mx-auto mt-2 mb-3 rounded-full"></div>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] opacity-80">Akses Literasi Digital</p>
        </div>

        @if (session('loginError'))
            <div class="bg-red-50 border border-red-200 text-red-600 text-[11px] p-4 rounded-2xl mb-6 text-center font-bold italic animate-pulse">
                {{ session('loginError') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 text-[11px] p-4 rounded-2xl mb-6 text-center font-bold italic">
                {{ session('success') }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5 relative z-10">
            @csrf

            <div class="space-y-1.5">
                <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-widest ml-2">Email Kampus</label>
                <input type="email" name="email" placeholder="nim@ith.ac.id"
                    class="custom-input w-full rounded-2xl py-4 px-5 text-sm outline-none text-slate-700 placeholder:text-slate-300 shadow-sm"
                    required>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-extrabold text-[#2D3E50] uppercase tracking-widest ml-2">Password Security</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="custom-input w-full rounded-2xl py-4 px-5 text-sm outline-none text-slate-700 placeholder:text-slate-300 shadow-sm"
                    required>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-[#FFD666] hover:bg-[#FFC107] text-[#2D3E50] font-black py-4 rounded-2xl shadow-xl shadow-yellow-500/20 transition-all duration-300 uppercase tracking-[0.2em] text-xs border-b-4 border-yellow-600 active:border-b-0 active:translate-y-1">
                    Masuk ke Sistem
                </button>
            </div>

            <p class="text-center text-[11px] text-slate-500 mt-8 font-semibold tracking-wide">
                Belum memiliki akun resmi?
                <a href="{{ route('register') }}" class="text-blue-700 font-black hover:text-blue-800 ml-1 underline underline-offset-4 decoration-2">DAFTAR DISINI</a>
            </p>
        </form>
    </div>

</body>

</html>
