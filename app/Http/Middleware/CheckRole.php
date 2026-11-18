<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika user memiliki salah satu role yang diizinkan, lanjutkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika kasir mencoba mengakses halaman selain kasir, arahkan kembali ke /kasir
        if ($user->role === 'Kasir') {
            return redirect()->route('kasir.index')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        // Default redirect jika tidak punya akses
        return redirect('/')->with('error', 'Akses ditolak.');
    }
}