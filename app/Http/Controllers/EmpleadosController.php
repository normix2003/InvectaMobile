<?php

namespace App\Http\Controllers;

use App\Models\empleados;

class EmpleadosController
{
    // Función para mostrar la vista de usuarios y los empleados registrados en la base de datos
    public function index()
    {
        // Se obtienen todos los empleados con la relación de usuario y rol
        $empleados = empleados::with('usuario.rol')->get();
        // Se retorna la vista de usuarios con los empleados
        return view('pages.usuarios.usuarios', ['empleados' => $empleados]);
    }

    // Función para mostrar la vista de detalle-usuario y todos los datos de un empleado en específico
    public function detalle_usuario($idEmpleados)
    {
        // Se obtiene un empleado en específico con la relación de usuario y rol
        $empleado = empleados::with('usuario.rol')->find($idEmpleados);
        // Se retorna la vista de detalle-usuario con los datos del empleado
        return view('pages.usuarios.detalle-usuario', ['empleado' => $empleado]);
    }

    // Función para eliminar un empleado de la base de datos
    public function destroy($idEmpleados)
    {
        // Se obtiene un empleado por medio de su idEmpleados
        $empleado = empleados::findOrFail($idEmpleados);
        // Se elimina el empleado
        $empleado->delete();
        // Se redirecciona a la vista de usuarios
        return redirect()->route('usuarios');
    }
}