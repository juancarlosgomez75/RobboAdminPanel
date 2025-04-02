<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRank
{
    public function handle(Request $request, Closure $next, $rank)
    {
        $user = Auth::user(); // Obtener usuario autenticado

        if(!$user){
            return redirect(route("login"));
        }
        elseif ($user->rank < $rank) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}

