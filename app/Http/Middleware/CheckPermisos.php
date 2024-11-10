<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermisos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permisos): Response
    {
        if (Auth::check()) {
            //$userPermisos = Auth::user()->rol->detallesroles->pluck('permisos.Nombre')->toArray();
            $userPermisos = Auth::user()->rol->detallesroles->where('Eliminar', 0)->pluck('permisos.Nombre')->toArray();
            //dd($userPermisos);
            if (collect($permisos)->every(fn($permiso) => in_array($permiso, $userPermisos))) {
                return $next($request);
            }
        }

        return redirect()->back();
    }
}
