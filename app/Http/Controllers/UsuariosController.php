<?php

namespace App\Http\Controllers;

use App\Models\detallesroles;
use App\Models\empleados;
use App\Models\usuarios;
use App\Models\roles;
use App\Models\permisos;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UsuariosController
{
    // Función para mostrar la vista con todos los empleados 
    public function index()
    {
        // Obtener todos los empleados con sus usuarios y roles
        $empleados = empleados::with('usuario.rol')->where('Eliminar', 0)->get();
        // Retornar la vista de usuarios con los empleados 
        return view('pages.usuarios.usuarios', ['empleados' => $empleados]);
    }

    // Función para mostrar la vista de nuevo-usuario con los roles registrados en la base de datos 
    public function nuevoUsuario()
    {
        // Obtener todos los roles registrados en la base de datos que no estén eliminados
        $roles = roles::all()->where('Eliminar', 0);
        // Retornar la vista de nuevo-usuario con los roles
        return view('pages.usuarios.nuevo-usuario', ['roles' => $roles]);
    }

    // Función para mostrar la vista de detalle-usuario con los datos de un empleado en específico
    public function detalleUsuario($idEmpleados)
    {
        // Obtener un empleado en específico con su usuario y rol
        $empleado = empleados::with([
            'usuario.rol.detallesroles' => function ($query) {
                $query->where('Eliminar', 0); // Filtra registros donde Eliminar es 0 en detallesroles
            },
            'usuario.rol.detallesroles.permisos' // Incluye los permisos relacionados
        ])->find($idEmpleados);
        $roles = roles::all()->where('Eliminar', 0);
        // Retornar la vista de detalle-usuario con los datos del empleado
        return view('pages.usuarios.detalle-usuario', ['empleado' => $empleado, 'roles' => $roles]);
    }

    // Función para almacenar un nuevo usuario en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Empleado' => 'required|string',
            'Apellidos' => 'required|string',
            'Email' => 'required|email',
            'Telefono' => 'required|numeric',
            'DUI' => 'required|string',
            'Nombre_Usuario' => 'required|string',
            'Contrasenia' => 'required|string',
            'Nombre' => 'required|string',
        ], [
            'Nombre_Empleado.required' => 'El campo del nombre del empleado no debe estar vacío.',
            'Apellidos.required' => 'El campo de los apellidos del empleado no debe estar vacío.',
            'Email.required' => 'El campo del correo electrónico no debe estar vacío.',
            'Email.email' => 'El correo electrónico no tiene un formato válido.',
            'Telefono.required' => 'El campo del teléfono no debe estar vacío.',
            'Telefono.numeric' => 'El teléfono debe ser un número.',
            'DUI.required' => 'El campo del DUI no debe estar vacío.',
            'Nombre_Usuario.required' => 'El campo del nombre de usuario no debe estar vacío.',
            'Contrasenia.required' => 'El campo de la contraseña no debe estar vacío.',
            'Nombre.required' => 'El campo del rol no debe estar vacío.',
        ]);
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
            $usuario['Eliminar'] = 0;
            $usuario = usuarios::create($usuario);
            // Verificar si el usuario se creó
            if ($usuario) {
                // Crear un nuevo empleado con los datos del empleado y el id del usuario
                $empleado['ID_Usuarios'] = $usuario->idUsuarios;
                $empleado['Eliminar'] = 0;
                empleados::create($empleado);

            }
        }

        // Redireccionar a la vista de usuarios
        return redirect()->route('usuarios');
    }

    public function update($idEmpleados, Request $request)
    {
        $request->validate([
            'Nombre_Empleado' => 'required|string',
            'Apellidos' => 'required|string',
            'Email' => 'required|email',
            'Telefono' => 'required|numeric',
            'DUI' => 'required|string',
            'Nombre_Usuario' => 'required|string',
            'Nombre' => 'required|string',
        ], [
            'Nombre_Empleado.required' => 'El campo del nombre del empleado no debe estar vacío.',
            'Apellidos.required' => 'El campo de los apellidos del empleado no debe estar vacío.',
            'Email.required' => 'El campo del correo electrónico no debe estar vacío.',
            'Email.email' => 'El correo electrónico no tiene un formato válido.',
            'Telefono.required' => 'El campo del teléfono no debe estar vacío.',
            'Telefono.numeric' => 'El teléfono debe ser un número.',
            'DUI.required' => 'El campo del DUI no debe estar vacío.',
            'Nombre_Usuario.required' => 'El campo del nombre de usuario no debe estar vacío.',
            'Nombre.required' => 'El campo del rol no debe estar vacío.',
        ]);
        $empleadoData = $request->only(['Nombre_Empleado', 'Apellidos', 'Email', 'Telefono', 'DUI']);
        $usuarioData = $request->only(['Nombre_Usuario']);
        $nombreRol = $request->input('Nombre');
        //dd($empleado, $usuario, $nombreRol, $permisos);
        $empleadoUpdate = empleados::find($idEmpleados);
        $usuarioUpdate = usuarios::where('idUsuarios', $empleadoUpdate->ID_Usuarios)->first();
        $idRolUpdate = roles::where('Nombre', $nombreRol)->first();

        if ($empleadoUpdate) {
            $empleadoUpdate->update($empleadoData);
        }

        if ($usuarioUpdate) {
            if ($nombreRol) {
                $usuarioUpdate->ID_Rol = $idRolUpdate->idRoles;
            }
            $usuarioUpdate->update($usuarioData);
        }
        return redirect()->route('detalle-usuario', ['idEmpleados' => $idEmpleados]);
    }

    // Función para eliminar un empleado de la base de datos
    public function destroy($idEmpleados)
    {
        // Obtener un empleado por medio de su idEmpleados
        $empleado = empleados::findOrFail($idEmpleados);
        $usuario = usuarios::find($empleado->ID_Usuarios);

        if (empty($empleado) || empty($usuario)) {
            return redirect()->route('usuarios')->withErrors(['error' => 'No se encontró el empleado o el usuario especificado.']);
        }

        $empleado['Eliminar'] = 1;
        $usuario['Eliminar'] = 1;
        $usuario->save();
        $empleado->save();
        // Redireccionar a la vista de usuarios con un mensaje de éxito
        return redirect()->route('usuarios')->with('success', 'Empleado eliminado exitosamente.');
    }

}
