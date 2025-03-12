<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthCustom
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Evitar redirigir en bucle si ya está en la página de login
            if ($request->routeIs('login')) {
                return $next($request);
            }

            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        return $next($request);
    }
}
