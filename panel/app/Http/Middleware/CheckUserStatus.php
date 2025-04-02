<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->activo == false) {
            Auth::logout(); // Cerrar sesión
            return redirect(route("login"));
        }

        return $next($request);
    }
}

