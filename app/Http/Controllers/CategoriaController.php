<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categorias;

class CategoriaController
{
    //Función para mostrar la vista de nueva-categoria
    public function index()
    {
        //Se retorna la vista de nueva-categoria
        return view('pages.inventario.nueva-categoria');
    }

    //Función para almacenar una nueva categoría en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Categoria' => 'required|string',
            'Descripcion' => 'required|string'
        ], [
            'Nombre_Categoria.required' => 'El campo de la categoría no debe estar vacío.',
            'Descripcion.required' => 'La descripción no debe estar vacía.'
        ]);
        //Se obtiene el nombre de la categoría y la descripción del formulario en la vista
        $categoria = $request->only(['Nombre_Categoria', 'Descripcion']);
        //Se crea una nueva categoría en la base de datos
        categorias::create($categoria);
        //Se redirecciona a la vista de nuevo-producto
        return redirect()->route('nuevo-producto')->with('success', 'Categoría creada correctamente.');
    }

}
