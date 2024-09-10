<?php

namespace App\Http\Controllers;


use App\Models\usuarios;
use App\Models\roles;
use App\Models\permisos;
use App\Models\detallesroles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Crear permisos y roles si no existen
        $this->crearPermisos();
        $this->crearRoles();

        // Verifica si existe un usuario con rol de administrador
        $adminRole = roles::where('Nombre', 'Administrador')->first();
        $adminExists = usuarios::where('ID_Rol', $adminRole->idRoles ?? 0)->exists();

        if (!$adminExists) {
            return view('pages.create-admin');
        }

        return view('pages.login');
    }

    public function crearAdmin(Request $request)
    {
        $adminRole = roles::where('Nombre', 'Administrador')->first();

        if (!$adminRole) {
            return back()->withErrors(['error' => 'El rol de Administrador no existe.']);
        }

        $adminExists = usuarios::where('ID_Rol', $adminRole->idRoles ?? 0)->exists();

        if ($adminExists) {
            return redirect()->route('login');
        }
        $admin = $request->only(['Nombre_Usuario', 'Contrasenia']);
        $admin['Contrasenia'] = Hash::make($admin['Contrasenia']);
        $admin['ID_Rol'] = $adminRole->idRoles;
        //dd($admin);
        usuarios::create($admin);


        return redirect()->route('login')->withErrors(['login_error' => 'Credenciales incorrectas']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['Nombre_Usuario', 'Contrasenia']);

        if (
            Auth::attempt([
                'Nombre_Usuario' => $credentials['Nombre_Usuario'],
                'password' => $credentials['Contrasenia']
            ])
        ) {
            // Si la autenticación es exitosa, redirige al home
            $user = Auth::user(); // Obtén el usuario autenticado
            session(['user' => $user]);
            return redirect()->route('home');
        } else {
            // Si la autenticación falla, redirige al login con un error
            return back()->withErrors(['login_error' => 'Credenciales incorrectas']);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    private function crearPermisos()
    {
        $permisos = ['Ver', 'Crear', 'Modificar', 'Eliminar'];

        foreach ($permisos as $permiso) {
            permisos::firstOrCreate(['Nombre' => $permiso]);
        }
    }
    private function crearRoles()
    {

        $roles = 'Administrador';
        $rol = roles::firstOrCreate(['Nombre' => $roles]);
        if ($rol->wasRecentlyCreated) {
            // El rol fue creado en este momento
            $this->asignarPermisos();
        }

    }

    private function asignarPermisos()
    {
        $roles = roles::where('Nombre', 'Administrador')->first();
        $permisos = permisos::all();

        foreach ($permisos as $permiso) {
            detallesroles::firstOrCreate([
                'ID_Roles' => $roles->idRoles,
                'ID_Permisos' => $permiso->idPermisos
            ]);
        }
    }


}
