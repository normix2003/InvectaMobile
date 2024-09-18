<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\producto;
use App\Models\marcas;
use Illuminate\Http\Request;

class InventarioController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $inventario = producto::with(['marca', 'categoria'])->get();
        return view('pages.inventario.inventario', ['inventario' => $inventario]);
    }

    public function nuevoProducto()
    {
        $marcas = marcas::all();
        $categorias = categorias::all();
        return view('pages.inventario.nuevo-producto', ['marcas' => $marcas, 'categorias' => $categorias]);
    }
    public function nuevaMarca()
    {
        return view('pages.inventario.nueva-marca');
    }
    public function nuevaCategoria()
    {
        return view('pages.inventario.nueva-categoria');
    }
    public function storeMarca(Request $request)
    {
        $marca = $request->input('Nombre_Marca');
        //dd($marca);
        if (empty($marca)) {
            return redirect()->route('inventario.marca');
        }
        marcas::create(['Nombre_Marca' => $marca]);
        return redirect()->route('nuevo-producto');
    }
    public function storeCategoria(Request $request)
    {
        $categoria = $request->only(['Nombre_Categoria', 'Descripcion']);
        //dd($categoria);
        if (empty($categoria['Nombre_Categoria'])) {
            return redirect()->route('inventario.categoria');
        }
        categorias::create($categoria);
        return redirect()->route('nuevo-producto');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $marca = $request->input('Nombre_Marca');
        $categoria = $request->input('Nombre_Categoria');
        $producto = $request->only(['Nombre_Producto', 'Descripcion', 'Precio', 'Cantidad']);

        if (empty($marca) || empty($categoria) || empty($producto)) {
            return redirect()->route('nuevo-producto');
        }

        $idMarca = marcas::where('Nombre_Marca', $marca)->first()->idMarcas;
        $idCategoria = categorias::where('Nombre_Categoria', $categoria)->first()->idCategorias;

        if ($idMarca && $idCategoria) {
            $producto['ID_Marca'] = $idMarca;
            $producto['ID_Categoria'] = $idCategoria;

            producto::create($producto);
            return redirect()->route('inventario');
        }

        return redirect()->route('nuevo-producto');

    }

    public function stock($idProductos)
    {
        //dd($idProductos);
        $producto = producto::findOrFail($idProductos);

        if (!$producto) {
            return redirect()->route('inventario');
        }
        return view('pages.inventario.stock', ['idProductos' => $idProductos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idProductos)
    {
        //
        $stock = $request->input('Cantidad');
        if (empty($stock) || $stock <= 0) {
            return redirect()->route('stock', ['idProductos' => $idProductos]);
        }

        $producto = producto::findOrFail($idProductos);

        if (!empty($producto)) {

            $cantiadActual = $producto->Cantidad;
            $nuevoStock = $cantiadActual + $stock;
            $producto->Cantidad = $nuevoStock;
            $producto->save();

        }
        return redirect()->route('inventario');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idProductos)
    {
        //

        $producto = producto::findOrFail($idProductos);
        if ($producto) {
            $producto->delete();
        }

        return redirect()->route('inventario');
    }
}
