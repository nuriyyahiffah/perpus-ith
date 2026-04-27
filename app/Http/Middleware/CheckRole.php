<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    
    // Jika role user ada dalam daftar yang diizinkan, silakan lewat
    if (in_array(strtolower($user->role), $roles)) {
        return $next($request);
    }

    // JIKA TIDAK DIIZINKAN, arahkan ke rumahnya masing-masing (biar tidak nyasar)
    $role = strtolower($user->role);
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'pustakawan') return redirect()->route('pustakawan.dashboard');
    if ($role === 'dosen') return redirect()->route('dosen.beranda');
    
    return redirect()->route('mahasiswa.beranda');
}
}
