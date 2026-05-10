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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  (Menangkap banyak role sekaligus)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
       
      if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    // Mengubah role user dan daftar role yang diminta menjadi huruf kecil semua sebelum dibandingkan
    $userRole = strtolower($user->role);
    $allowedRoles = array_map('strtolower', $roles);

    if (in_array($userRole, $allowedRoles)) {
        return $next($request);
    }

    abort(403, 'AKSES DITOLAK. ROLE AKUN ANDA ADALAH "' . $user->role . '", SEDANGKAN HALAMAN INI MEMERLUKAN SALAH SATU DARI ROLE BERIKUT: ' . implode(', ', $roles));
}
}