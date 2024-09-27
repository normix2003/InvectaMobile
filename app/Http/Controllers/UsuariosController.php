<?php

namespace App\Http\Controllers;

use App\Models\empleados;
use App\Models\usuarios;
use App\Models\roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UsuariosController
{
    // Función para mostrar la vista con todos los empleados 
    public function index()
    {
        // Obtener todos los empleados con sus usuarios y roles
        $empleados = empleados::with('usuario.rol')->get();
        // Retornar la vista de usuarios con los empleados 
        return view('pages.usuarios.usuarios', ['empleados' => $empleados]);
    }

    // Función para mostrar la vista de nuevo-usuario con los roles registrados en la base de datos 
    public function nuevoUsuario()
    {
        // Obtener todos los roles registrados en la base de datos
        $roles = roles::all();
        // Retornar la vista de nuevo-usuario con los roles
        return view('pages.usuarios.nuevo-usuario', ['roles' => $roles]);
    }

    // Función para mostrar la vista de detalle-usuario con los datos de un empleado en específico
    public function detalleUsuario($idEmpleados)
    {
        // Obtener un empleado en específico con su usuario y rol
        $empleado = empleados::with('usuario.rol.detallesroles.permisos')->find($idEmpleados);

        // Retornar la vista de detalle-usuario con los datos del empleado
        return view('pages.usuarios.detalle-usuario', ['empleado' => $empleado]);
    }

    // Función para almacenar un nuevo usuario en la base de datos
    public function store(Request $request)
    {
        // Obtener los datos del empleado, usuario y rol de la vista
        $empleado = $request->only(['Nombre_Empleado', 'Apellidos', 'Email', 'Telefono', 'DUI']);
        $usuario = $request->only(['Nombre_Usuario', 'Contrasenia']);
        $rol = $request->input('Nombre');

        // Verificar si los datos están vacíos
        if (empty($rol) || empty($usuario) || empty($empleado)) {
            return redirect()->route('usuarios');
        }

        // Obtener el id del rol
        $idRol = roles::where('Nombre', $rol)->first()->idRoles;

        // Verificar si el id del rol existe
        if ($idRol) {
            // Crear un nuevo usuario con los datos del usuario y el id del rol
            $usuario['ID_Rol'] = $idRol;
            // Encriptar la contraseña del usuario
            $usuario['Contrasenia'] = Hash::make($usuario['Contrasenia']);
            $usuario = usuarios::create($usuario);
            // Verificar si el usuario se creó
            if ($usuario) {
                // Crear un nuevo empleado con los datos del empleado y el id del usuario
                $empleado['ID_Usuarios'] = $usuario->idUsuarios;
                empleados::create($empleado);

            }
        }

        // Redireccionar a la vista de usuarios
        return redirect()->route('usuarios');
    }


    // Función para eliminar un empleado de la base de datos
    public function destroy($idEmpleados)
    {
        // Obtener un empleado por medio de su idEmpleados
        $empleado = empleados::findOrFail($idEmpleados);
        $usuario = usuarios::find($empleado->ID_Usuarios);

        // Verificar si el empleado y el usuario existen
        if ($empleado && $usuario) {
            // Eliminar el empleado y el usuario
            $empleado->delete();
            $usuario->delete();
        }
        // Redireccionar a la vista de usuarios
        return redirect()->route('usuarios');
    }

}
