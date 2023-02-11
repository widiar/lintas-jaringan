<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotPelanggan
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->hasRole('Pelanggan')) {
            return to_route('home');
        }
        return $next($request);
    }
}
