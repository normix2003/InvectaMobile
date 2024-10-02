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
        $roles = roles::with('detallesroles.permisos')->where('Eliminar', 0)->get();
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
        $rol = roles::firstOrCreate(['Nombre' => $nombreRol, 'Eliminar' => 0]);

        //si permisos es un array y contiene el valor 'Todos' se asignan todos los permisos al rol
        if (is_array($permisos) && in_array('Todos', $permisos)) {
            //Obtener todos los permisos
            $permisos = permisos::all();
            //Asignar todos los permisos al rol
            foreach ($permisos as $permiso) {
                detallesroles::create(['ID_Roles' => $rol->idRoles, 'ID_Permisos' => $permiso->idPermisos, 'Eliminar' => 0]);
            }
            //Redireccionar a la vista de roles
            return redirect()->route('roles.roles');
        }
        //Obtener los id de los permisos seleccionados
        $idpermisos = permisos::whereIn('Nombre', $permisos)->get()->pluck('idPermisos')->toArray();
        //Asignar los permisos seleccionados al rol
        foreach ($idpermisos as $idPermiso) {
            detallesroles::create(['ID_Roles' => $rol->idRoles, 'ID_Permisos' => $idPermiso, 'Eliminar' => 0]);
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
            $detallesRoles = detallesroles::where('ID_Roles', $idRoles)->get();
            //Si los detalles de roles no estan vacios
            if (!empty($detallesRoles)) {
                //Eliminar los detalles de roles
                foreach ($detallesRoles as $detalleRol) {
                    $detalleRol['Eliminar'] = 1;
                    $detalleRol->save();
                }
                $rol['Eliminar'] = 1;
                $rol->save();
            }
            //Redireccionar a la vista de roles
            return redirect()->route('roles.roles')->with('success', 'Rol eliminado exitosamente.');
        }
        return redirect()->route('roles.roles')->withErrors(['error' => 'No se encontró el rol especificado.']);

    }
}
