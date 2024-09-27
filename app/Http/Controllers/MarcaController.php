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
        //Se obtiene el nombre de la marca del formulario en la vista
        $marca = $request->input('Nombre_Marca');
        //Si el campo de la marca está vacío, se redirecciona a la vista de nueva-marca
        if (empty($marca)) {
            return redirect()->route('inventario.marca');
        }
        //Se crea una nueva marca en la base de datos
        marcas::create(['Nombre_Marca' => $marca]);
        //Se redirecciona a la vista de nuevo-producto
        return redirect()->route('nuevo-producto');
    }

}
