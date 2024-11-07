<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\marcas;
class MarcaController
{
    //Función para mostrar la vista de nueva-marca
    public function index()
    {
        $marcas = marcas::where('Eliminar', 0)->paginate(6);
        return view('pages.inventario.marcas', ['marcas' => $marcas]);
    }

    public function nuevaMarca(Request $request)
    {
        $sourceView = $request->query('source');
        return view('pages.inventario.nueva-marca', ['source' => $sourceView]);
    }

    public function detalleMarca($idMarcas)
    {
        $marca = marcas::find($idMarcas);
        return view('pages.inventario.detalle-marca', ['marca' => $marca]);
    }

    public function update($idMarcas, Request $request)
    {
        $request->validate([
            'Nombre_Marca' => 'required|string'
        ], [
            'Nombre_Marca.required' => 'El campo de la marca no debe estar vacío.'
        ]);
        $Nombre = $request->input('Nombre_Marca');
        $marca = marcas::find($idMarcas);
        $marca->Nombre_Marca = $Nombre;
        $marca->save();
        return redirect()->route('marcas')->with('success', 'Marca actualizada correctamente.');
    }
    //Función para almacenar una nueva marca en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Marca' => 'required|string'
        ], [
            'Nombre_Marca.required' => 'El campo de la marca no debe estar vacío.'
        ]);
        $sourceView = $request->query('source');
        //Se obtiene el nombre de la marca del formulario en la vista
        $marca = $request->input('Nombre_Marca');
        //Se crea una nueva marca en la base de datos
        marcas::create(['Nombre_Marca' => $marca, 'Eliminar' => 0]);
        //Se redirecciona a la vista de nuevo-producto
        if ($sourceView == 'nuevo-producto') {
            return redirect()->route('nuevo-producto')->with('success', 'Marca creada correctamente.');
        } else if ($sourceView == 'marcas') {
            return redirect()->route('marcas')->with('success', 'Marca creada correctamente.');
        }
    }

    public function destroy($idMarcas)
    {
        $marca = marcas::find($idMarcas);
        $marca->Eliminar = 1;
        $marca->save();
        return redirect()->route('marcas')->with('success', 'Marca eliminada correctamente.');
    }
}
