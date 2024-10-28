<?php

namespace App\Http\Controllers;


use App\Models\empleados;
use App\Models\usuarios;
use App\Models\roles;
use App\Models\permisos;
use App\Models\detallesroles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController
{
    //Funcion para mostrar la vista de login
    public function index()
    {
        // Crear permisos y roles si no existen
        $this->crearPermisos();
        $this->crearRoles();

        // Verifica si existe un usuario con rol de administrador
        $adminRole = roles::where('Nombre', 'Administrador')->first();
        $adminExists = usuarios::where('ID_Rol', $adminRole->idRoles ?? 0)->exists();

        // Si no existe un usuario con rol de administrador, redirige a la vista de creación de administrador
        if (!$adminExists) {
            return view('pages.create-admin');
        }
        //Retornar la vista de login
        return view('pages.login');
    }

    //Funcion para crear un administrador
    public function crearAdmin(Request $request)
    {
        // Verifica si existe un usuario con rol de administrador
        $adminRole = roles::where('Nombre', 'Administrador')->first();

        // Si no existe el rol de administrador, redirige con un error
        if (!$adminRole) {
            return back()->withErrors(['error' => 'El rol de Administrador no existe.']);
        }

        // Verifica si existe un usuario con rol de administrador
        $adminExists = usuarios::where('ID_Rol', $adminRole->idRoles ?? 0)->exists();

        // Si existe un usuario con rol de administrador, redirige a la vista de login
        if ($adminExists) {
            return redirect()->route('login');
        }
        //Obtener los datos del administrador de la vista
        $admin = $request->only(['Nombre_Usuario', 'Contrasenia']);
        $admin['Contrasenia'] = Hash::make($admin['Contrasenia']);

        //Asignar el rol de administrador al usuario
        $admin['ID_Rol'] = $adminRole->idRoles;

        //Obtener los datos del empleado de la vista
        $adminEmpleado = $request->only(['Nombre_Empleado', 'Apellidos', 'Email', 'Telefono', 'DUI']);

        //Crear un usuario con el rol de administrador
        $usuario = usuarios::create($admin);

        //Si el usuario se creó correctamente, crear un empleado con los datos del administrador
        if ($usuario) {
            $adminEmpleado['ID_Usuarios'] = $usuario->idUsuarios;
            empleados::create($adminEmpleado);
        }

        //Redirigir a la vista de login
        return redirect()->route('login')->withErrors(['login_error' => 'Credenciales incorrectas']);
    }

    //Funcion para autenticar un usuario
    public function login(Request $request)
    {
        //Obtener las credenciales del usuario de la vista
        $credentials = $request->only(['Nombre_Usuario', 'Contrasenia']);

        //Verificar si las credenciales son correctas
        if (
            Auth::attempt([
                'Nombre_Usuario' => $credentials['Nombre_Usuario'],
                'password' => $credentials['Contrasenia']
            ])
        ) {
            //Si la autenticación es correcta, redirigir a la vista de home
            return redirect()->route('home');

        } else {
            // Si la autenticación falla, redirige al login con un error
            return back()->withErrors(provider: ['login_error' => 'Credenciales incorrectas']);
        }
    }

    //Funcion para cerrar la sesion de un usuario
    public function logout()
    {
        //Cerrar la sesion del usuario y redirigir al login
        Auth::logout();
        return redirect()->route('login');
    }

    //Funcion para crear los permisos
    private function crearPermisos()
    {
        //Permisos a crear en la base de datos
        $permisos = ['Ver', 'Crear', 'Modificar', 'Eliminar'];

        //Crear los permisos en la base de datos si no existen
        foreach ($permisos as $permiso) {
            permisos::firstOrCreate(['Nombre' => $permiso]);
        }
    }

    //Funcion para crear los roles
    private function crearRoles()
    {
        //Rol a crear en la base de datos si no existe
        $roles = 'Administrador';

        //Crear el rol en la base de datos si no existe
        $rol = roles::firstOrCreate(['Nombre' => $roles, ['Eliminar' => 0]]);
        //Si el rol fue creado en este momento, asignarle los permisos
        if ($rol->wasRecentlyCreated) {
            // Asignar permisos al rol
            $this->asignarPermisos();
        }

    }

    //Funcion para asignar permisos al rol de administrador
    private function asignarPermisos()
    {
        //Obtener el rol de administrador y los permisos de la base de datos
        $roles = roles::where('Nombre', 'Administrador')->first();
        $permisos = permisos::all();

        //Asignar los permisos al rol de administrador  
        foreach ($permisos as $permiso) {
            //Crear los detalles de los roles con los permisos asignados al rol de administrador si no existen
            detallesroles::firstOrCreate([
                'ID_Roles' => $roles->idRoles,
                'ID_Permisos' => $permiso->idPermisos,
                'Eliminar' => 0
            ]);
        }
    }

}
