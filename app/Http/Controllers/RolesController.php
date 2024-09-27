<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\roles;
use App\Models\permisos;
use App\Models\detallesroles;

class RolesController
{
    //Funcion para mostrar la vista de roles con los roles y permisos
    public function index()
    {
        //Obtener todos los roles con sus permisos
        $roles = roles::with('detallesroles.permisos')->get();
        //Retornar la vista de roles con los roles y permisos
        return view('pages.roles.roles', ['roles' => $roles]);
    }
    //Funcion para mostrar la vista de nuevo rol
    public function nuevoRol()
    {
        //Retornar la vista de nuevo rol
        return view('pages.roles.nuevo-rol');
    }
    //Funcion para mostrar la vista de detalles de un rol
    public function detallesRoles($idRoles)
    {
        //Obtener un rol con sus permisos
        $roles = roles::with('detallesroles.permisos')->where('idRoles', $idRoles)->first();
        //Retornar la vista de detalles de un rol
        return view('pages.roles.detalle-rol', ['roles' => $roles]);
    }
    //Funcion para guardar un rol
    public function store(Request $request)
    {
        //Obtener el nombre del rol y los permisos
        $nombreRol = $request->input('Nombre');
        $permisos = $request->input('opcion', []);

        //Crear un rol si no existe
        $rol = roles::firstOrCreate(['Nombre' => $nombreRol]);

        //si permisos es un array y contiene el valor 'Todos' se asignan todos los permisos al rol
        if (is_array($permisos) && in_array('Todos', $permisos)) {
            //Obtener todos los permisos
            $permisos = permisos::all();
            //Asignar todos los permisos al rol
            foreach ($permisos as $permiso) {
                detallesroles::create(['ID_Roles' => $rol->idRoles, 'ID_Permisos' => $permiso->idPermisos]);
            }
            //Redireccionar a la vista de roles
            return redirect()->route('roles.roles');
        }
        //Obtener los id de los permisos seleccionados
        $idpermisos = permisos::whereIn('Nombre', $permisos)->get()->pluck('idPermisos')->toArray();
        //Asignar los permisos seleccionados al rol
        foreach ($idpermisos as $idPermiso) {
            detallesroles::create(['ID_Roles' => $rol->idRoles, 'ID_Permisos' => $idPermiso]);
        }
        //Redireccionar a la vista de roles
        return redirect()->route('roles.roles');
    }

    //Funcion para eliminar un rol
    public function destroy($idRoles)
    {
        //Obtener un rol por su id 
        $rol = roles::findOrFail($idRoles);
        // Si el rol no esta vacio
        if (!empty($rol)) {
            //Obtener los id de los detalles de roles
            $detallesRolesIds = detallesroles::where('ID_Roles', $idRoles)->pluck('idDetalles_Roles')->toArray();
            //Si los detalles de roles no estan vacios
            if (!empty($detallesRolesIds)) {
                //Eliminar los detalles de roles
                detallesroles::destroy($detallesRolesIds);
                $rol->delete();
            }
        }
        //Redireccionar a la vista de roles
        return redirect()->route('roles.roles');
    }
}
