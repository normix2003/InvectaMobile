<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\roles;
use App\Models\permisos;
use App\Models\detallesroles;

class RolesController
{
    //
    public function index()
    {
        $roles = roles::with('detallesroles.permisos')->get();
        return view('pages.roles.roles', ['roles' => $roles]);
    }
    public function nuevoRol()
    {
        return view('pages.roles.nuevo-rol');
    }
    public function detallesRoles($idRoles)
    {
        $roles = roles::with('detallesroles.permisos')->where('idRoles', $idRoles)->first();
        return view('pages.roles.detalle-rol', ['roles' => $roles]);
    }
    public function store(Request $request)
    {
        $nombreRol = $request->input('Nombre');
        $permisos = $request->input('opcion', []);

        $rol = roles::firstOrCreate(['Nombre' => $nombreRol]);
        if (is_array($permisos) && in_array('Todos', $permisos)) {
            $permisos = permisos::all();
            foreach ($permisos as $permiso) {
                detallesroles::create(['ID_Roles' => $rol->idRoles, 'ID_Permisos' => $permiso->idPermisos]);
            }
            return redirect()->route('roles.roles');
        }

        $idpermisos = permisos::whereIn('Nombre', $permisos)->get()->pluck('idPermisos')->toArray();
        foreach ($idpermisos as $idPermiso) {
            detallesroles::create(['ID_Roles' => $rol->idRoles, 'ID_Permisos' => $idPermiso]);
        }

        return redirect()->route('roles.roles');
    }
    public function destroy($idRoles)
    {
        //dd($idRoles);
        $rol = roles::findOrFail($idRoles);
        if (!empty($rol)) {
            $detallesRolesIds = detallesroles::where('ID_Roles', $idRoles)->pluck('idDetalles_Roles')->toArray();
            if (!empty($detallesRolesIds)) {
                detallesroles::destroy($detallesRolesIds);
                $rol->delete();
            }
        }



        return redirect()->route('roles.roles');
    }
}
