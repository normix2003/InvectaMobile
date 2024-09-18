<?php

namespace App\Http\Controllers;

use App\Models\empleados;
use App\Models\usuarios;
use App\Models\roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UsuariosController
{
    public function index()
    {
        // Obtener todos los empleados con sus usuarios y roles
        $empleados = empleados::select('idEmpleados', 'ID_Usuarios')
            ->with([
                'usuario' => function ($query) {
                    $query->select('idUsuarios', 'Nombre_Usuario', 'ID_Rol') // Seleccionar campos necesarios
                        ->with('rol:idRoles,Nombre');
                }
            ])
            ->get();

        // Obtener el rol de Administrador
        $adminRole = roles::where('Nombre', 'Administrador')->first();

        // Obtener el usuario administrador
        $admin = usuarios::select('idUsuarios', 'Nombre_Usuario', 'ID_Rol') // Seleccionar los campos necesarios
            ->where('ID_Rol', $adminRole->idRoles ?? 0)
            ->with('rol:idRoles,Nombre')
            ->first();

        return view('pages.usuarios.usuarios', ['empleados' => $empleados, 'admin' => $admin]);
    }
    public function nuevoUsuario()
    {
        $roles = roles::all();
        return view('pages.usuarios.nuevo-usuario', ['roles' => $roles]);
    }
    public function detalleUsuario($idEmpleados)
    {
        $empleado = empleados::with('usuario.rol.detallesroles.permisos')->find($idEmpleados);
        //dd($empleado->usuario->rol->detallesroles->pluck('permisos.Nombre'));
        return view('pages.usuarios.detalle-usuario', ['empleado' => $empleado]);
    }

    public function store(Request $request)
    {
        $empleado = $request->only(['Nombre_Empleado', 'Apellidos', 'Email', 'Telefono', 'DUI']);
        $usuario = $request->only(['Nombre_Usuario', 'Contrasenia']);
        $rol = $request->input('Nombre');

        if (empty($rol) || empty($usuario) || empty($empleado)) {
            return redirect()->route('usuarios');
        }


        $idRol = roles::where('Nombre', $rol)->first()->idRoles;

        if ($idRol) {
            $usuario['ID_Rol'] = $idRol;
            $usuario['Contrasenia'] = Hash::make($usuario['Contrasenia']);
            $usuario = usuarios::create($usuario);
            if ($usuario) {
                $empleado['ID_Usuarios'] = $usuario->idUsuarios;
                empleados::create($empleado);

            }
        }

        return redirect()->route('usuarios');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, empleados $empleados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idEmpleados)
    {
        $empleado = empleados::findOrFail($idEmpleados);
        $usuario = usuarios::find($empleado->ID_Usuarios);

        if ($empleado && $usuario) {
            $empleado->delete();
            $usuario->delete();
        }
        return redirect()->route('usuarios');
    }

}
