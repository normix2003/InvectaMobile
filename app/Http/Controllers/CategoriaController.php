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
        //Se obtiene el nombre de la categoría y la descripción del formulario en la vista
        $categoria = $request->only(['Nombre_Categoria', 'Descripcion']);
        //Si el campo de la categoría está vacío, se redirecciona a la vista de nueva-categoria
        if (empty($categoria['Nombre_Categoria'])) {
            return redirect()->route('inventario.categoria');
        }
        //Se crea una nueva categoría en la base de datos
        categorias::create($categoria);
        //Se redirecciona a la vista de nuevo-producto
        return redirect()->route('nuevo-producto');
    }

}
