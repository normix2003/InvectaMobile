<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\producto;
use App\Models\marcas;
use Illuminate\Http\Request;

class InventarioController
{
    // Función para mostrar la vista de inventario y todos los productos registrados en la base de datos
    public function index()
    {
        // Se obtienen todos los productos con la relación de marca y categoría
        $inventario = producto::with(['marca', 'categoria'])->get();
        // Se retorna la vista de inventario con los productos
        return view('pages.inventario.inventario', ['inventario' => $inventario]);
    }

    // Función para mostrar la vista de nuevo-producto y las marcas y categorías registradas en la base de datos
    public function nuevoProducto()
    {
        // Se obtienen todas las marcas y categorías
        $marcas = marcas::all();
        // Se obtienen todas las categorías
        $categorias = categorias::all();
        // Se retorna la vista de nuevo-producto con las marcas y categorías
        return view('pages.inventario.nuevo-producto', ['marcas' => $marcas, 'categorias' => $categorias]);
    }
    // Función para almacenar un nuevo producto en la base de datos
    public function store(Request $request)
    {
        // Se obtiene el nombre de la marca, la categoría y los datos del producto del formulario en la vista
        $marca = $request->input('Nombre_Marca');
        $categoria = $request->input('Nombre_Categoria');
        $producto = $request->only(['Nombre_Producto', 'Descripcion', 'Precio', 'Cantidad']);

        // Si alguno de los campos está vacío, se redirecciona a la vista de nuevo-producto
        if (empty($marca) || empty($categoria) || empty($producto)) {
            return redirect()->route('nuevo-producto');
        }

        // Se obtiene el id de la marca y la categoría
        $idMarca = marcas::where('Nombre_Marca', $marca)->first()->idMarcas;
        $idCategoria = categorias::where('Nombre_Categoria', $categoria)->first()->idCategorias;

        // Si se obtiene el id de la marca y la categoría, se crea un nuevo producto en la base de datos
        if ($idMarca && $idCategoria) {
            $producto['ID_Marca'] = $idMarca;
            $producto['ID_Categoria'] = $idCategoria;

            producto::create($producto);
            return redirect()->route('inventario');
        }

        // Si no se obtiene el id de la marca y la categoría, se redirecciona a la vista de nuevo-producto
        return redirect()->route('nuevo-producto');

    }

    // Función para mostrar la vista de stock y el producto en específico
    public function stock($idProductos)
    {
        // Se obtiene un producto en específico por medio de su idProductos
        $producto = producto::findOrFail($idProductos);

        // Si no se obtiene el producto, se redirecciona a la vista de inventario
        if (!$producto) {
            return redirect()->route('inventario');
        }
        // Se retorna la vista de stock con el producto en específico
        return view('pages.inventario.stock', ['idProductos' => $idProductos]);
    }

    // Función para actualizar el stock de un producto en la base de datos
    public function update(Request $request, $idProductos)
    {
        // Se obtiene la cantidad del formulario en la vista
        $stock = $request->input('Cantidad');
        // Si la cantidad está vacía o es menor o igual a 0, se redirecciona a la vista de stock
        if (empty($stock) || $stock <= 0) {
            return redirect()->route('stock', ['idProductos' => $idProductos]);
        }

        // Se obtiene un producto en específico por medio de su idProductos
        $producto = producto::findOrFail($idProductos);

        // Si se obtiene el producto, se actualiza el stock
        if (!empty($producto)) {

            $cantiadActual = $producto->Cantidad;
            $nuevoStock = $cantiadActual + $stock;
            $producto->Cantidad = $nuevoStock;
            $producto->save();

        }
        // Se redirecciona a la vista de inventario
        return redirect()->route('inventario');
    }

    // Función para eliminar un producto de la base de datos
    public function destroy($idProductos)
    {
        // Se obtiene un producto en específico por medio de su idProductos
        $producto = producto::findOrFail($idProductos);
        // Si se obtiene el producto, se elimina
        if ($producto) {
            $producto->delete();
        }
        // Se redirecciona a la vista de inventario
        return redirect()->route('inventario');
    }
}
