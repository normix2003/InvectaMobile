<?php

namespace App\Http\Controllers;

use App\Models\empleados;
use Illuminate\Http\Request;

class EmpleadosController
{
    public function index()
    {
        $empleados = empleados::with('usuario.rol')->get();
        return view('pages.usuarios.usuarios', ['empleados' => $empleados]);
    }

    public function detalle_usuario($idEmpleados)
    {
        $empleado = empleados::with('usuario.rol')->find($idEmpleados);
        return view('pages.usuarios.detalle-usuario', ['empleado' => $empleado]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(empleados $empleados)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(empleados $empleados)
    {
        //
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
        $empleado->delete();
        return redirect()->route('usuarios');
    }
}