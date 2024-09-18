<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdministrador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check() || Auth::user()->rol->Nombre !== 'Administrador') {
            return redirect()->back();
        }
        //$userPermisos = Auth::user()->rol->detallesroles->pluck('permisos.Nombre')->toArray();
        // if (collect($permisos)->every(fn($permiso) => in_array($permiso, $userPermisos))) {
        //     return $next($request);
        // }
        return $next($request);


    }
}
