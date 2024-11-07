<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categorias;

class CategoriaController
{
    //Función para mostrar la vista de nueva-categoria
    public function index()
    {
        //Se obtienen todas las categorías registradas en la base de datosa =
        $categorias = categorias::where('Eliminar', 0)->paginate(6);
        //Se retorna la vista de categorias con las categorías obtenidas
        return view('pages.inventario.categorias', ['categorias' => $categorias]);

    }

    public function nuevaCategoria(Request $request)
    {
        $sourceView = $request->query('source');
        //Se retorna la vista de nueva-categoria
        return view('pages.inventario.nueva-categoria', ['source' => $sourceView]);

    }

    public function detalleCategoria($idCateogria)
    {
        //Se obtiene la categoría con el id proporcionado
        $categoria = categorias::find($idCateogria);
        //Se retorna la vista de detalle-categoria con la categoría obtenida
        return view('pages.inventario.detalle-categoria', ['categoria' => $categoria]);
    }

    //Función para almacenar una nueva categoría en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Categoria' => 'required|string',
        ], [
            'Nombre_Categoria.required' => 'El campo de la categoría no debe estar vacío.',
        ]);
        $sourceView = $request->query('source');
        //Se obtiene el nombre de la categoría y la descripción del formulario en la vista
        $categoria = $request->only(['Nombre_Categoria', 'Descripcion']);
        $categoria['Eliminar'] = 0;
        //Se crea una nueva categoría en la base de datos
        categorias::create($categoria);
        //Se redirecciona a la vista de nuevo-producto
        if ($sourceView == 'nuevo-producto') {
            return redirect()->route('nuevo-producto')->with('success', 'Categoría creada correctamente.');
        } else if ($sourceView == 'categorias') {
            return redirect()->route('categorias')->with('success', 'Categoría creada correctamente.');
        }
    }

    public function update(Request $request, $idCategoria)
    {
        $request->validate([
            'Nombre_Categoria' => 'required|string',
        ], [
            'Nombre_Categoria.required' => 'El campo de la categoría no debe estar vacío.',
        ]);
        //Se obtiene la categoría con el id proporcionado
        $categoria = categorias::find($idCategoria);
        //Se obtiene el nombre de la categoría y la descripción del formulario en la vista
        $categoria->Nombre_Categoria = $request->Nombre_Categoria;
        $categoria->Descripcion = $request->Descripcion;
        //Se actualiza la categoría en la base de datos
        $categoria->save();
        //Se redirecciona a la vista de categorías
        return redirect()->route('categorias')->with('success', 'Categoría actualizada correctamente.');
    }
    public function destroy($idCategorias)
    {
        //Se obtiene la categoría con el id proporcionado
        $categoria = categorias::find($idCategorias);
        //Se elimina la categoría
        $categoria->Eliminar = 1;
        $categoria->save();
        //Se redirecciona a la vista de categorías
        return redirect()->route('categorias')->with('success', 'Categoría eliminada correctamente.');
    }

}
