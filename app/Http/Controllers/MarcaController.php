<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\marcas;
class MarcaController
{
    //Función para mostrar la vista de nueva-marca
    public function index()
    {
        //Se retorna la vista de nueva-marca
        return view('pages.inventario.nueva-marca');
    }

    //Función para almacenar una nueva marca en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Marca' => 'required|string'
        ], [
            'Nombre_Marca.required' => 'El campo de la marca no debe estar vacío.'
        ]);
        //Se obtiene el nombre de la marca del formulario en la vista
        $marca = $request->input('Nombre_Marca');
        //Se crea una nueva marca en la base de datos
        marcas::create(['Nombre_Marca' => $marca]);
        //Se redirecciona a la vista de nuevo-producto
        return redirect()->route('nuevo-producto')->with('success', 'Marca creada correctamente.');
    }

}
