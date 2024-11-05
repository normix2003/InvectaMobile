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
        $inventario = producto::with(['marca', 'categoria'])->where('Eliminar', 0)->paginate(5);
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
        $todosVacios = empty($request->input('Nombre_Marca')) &&
            empty($request->input('Nombre_Categoria')) &&
            empty($request->input('Nombre_Producto')) &&
            empty($request->input('Descripcion')) &&
            empty($request->input('Precio')) &&
            empty($request->input('Cantidad'));

        // Si todos los campos están vacíos, redirigir con un mensaje general
        if ($todosVacios) {
            return redirect()->route('nuevo-producto')->withErrors([
                'general' => 'Todos los campos son obligatorios y no deben estar vacíos.'
            ]);
        }

        $request->validate([
            'Nombre_Marca' => 'required|string',
            'Nombre_Categoria' => 'required|string',
            'Nombre_Producto' => 'required|string',
            'Descripcion' => 'required|string',
            'Precio' => 'required|numeric|min:0',
            'Cantidad' => 'required|integer|min:1',
        ], [
            'Nombre_Marca.required' => 'El campo de la marca no debe estar vacío.',
            'Nombre_Categoria.required' => 'El campo de la categoría no debe estar vacío.',
            'Nombre_Producto.required' => 'El campo del producto no debe estar vacío.',
            'Descripcion.required' => 'La descripción no debe estar vacía.',
            'Precio.required' => 'El precio es obligatorio y debe ser un número.',
            'Precio.numeric' => 'El precio debe ser un valor numérico.',
            'Cantidad.required' => 'La cantidad es obligatoria y debe ser un número entero.',
            'Cantidad.integer' => 'La cantidad debe ser un número entero.',
            'Cantidad.min' => 'La cantidad debe ser al menos 1.',
        ]);
        // Se obtiene el nombre de la marca, la categoría y los datos del producto del formulario en la vista
        $marca = $request->input('Nombre_Marca');
        $categoria = $request->input('Nombre_Categoria');
        $producto = $request->only(['Nombre_Producto', 'Descripcion', 'Precio', 'Cantidad']);

        // Se obtiene el id de la marca y la categoría
        $idMarca = marcas::where('Nombre_Marca', $marca)->first()->idMarcas;
        $idCategoria = categorias::where('Nombre_Categoria', $categoria)->first()->idCategorias;

        // Si se obtiene el id de la marca y la categoría, se crea un nuevo producto en la base de datos
        if ($idMarca && $idCategoria) {
            $producto['ID_Marca'] = $idMarca;
            $producto['ID_Categoria'] = $idCategoria;
            $producto['Precio'] = (float) str_replace(',', '', $producto['Precio']);
            $producto['Eliminar'] = 0;
            producto::create($producto);
            return redirect()->route('inventario')->with('success', 'Producto creado exitosamente.');
        }

        // Si no se obtiene el id de la marca y la categoría, se redirecciona a la vista de nuevo-producto
        return redirect()->route('nuevo-producto')->withErrors(['error' => 'No se encontró la marca o la categoría especificada.']);

    }

    // Función para mostrar la vista de stock y el producto en específico
    public function stock($idProductos)
    {
        if (empty($idProductos)) {
            // Se redirecciona a la vista de inventario con un mensaje de error
            return redirect()->route('stock', ['idProductos' => $idProductos])->withErrors('error', 'El id del producto es obligatorio.');
        }
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
        $request->validate([
            'Cantidad' => 'required|integer|min:1',
        ], [
            'Cantidad.required' => 'La cantidad es obligatoria y debe ser un número entero.',
            'Cantidad.integer' => 'La cantidad debe ser un número entero.',
            'Cantidad.min' => 'La cantidad debe ser al menos 1.',
        ]);

        // Se obtiene la cantidad del formulario en la vista
        $stock = $request->input('Cantidad');
        // Se obtiene un producto en específico por medio de su idProductos
        $producto = producto::findOrFail($idProductos);

        // Si se obtiene el producto, se actualiza el stock
        if (!empty($producto)) {
            $cantiadActual = $producto->Cantidad;
            $nuevoStock = $cantiadActual + $stock;
            $producto->Cantidad = $nuevoStock;
            $producto->save();
            return redirect()->route('inventario')->with('success', 'Stock actualizado exitosamente.');
        }

        // Se redirecciona a la vista de inventario
        return redirect()->route('stock', ['idProductos' => $idProductos])->withErrors(['error' => 'No se encontró el producto especificado.']);
    }

    // Función para eliminar un producto de la base de datos
    public function destroy($idProductos)
    {
        if (empty($idProductos)) {
            // Se redirecciona a la vista de inventario con un mensaje de error
            return redirect()->route('inventario')->withErrors('error', 'El id del producto es obligatorio.');
        }
        // Se obtiene un producto en específico por medio de su idProductos
        $producto = producto::findOrFail($idProductos);
        // Si se obtiene el producto, se elimina
        if (empty($producto)) {
            // Se redirecciona a la vista de inventario con un mensaje de error
            return redirect()->route('inventario')->withErrors(['error' => 'No se encontró el producto especificado.']);
        }
        $producto['Eliminar'] = 1;
        $producto->save();
        // Se redirecciona a la vista de inventario con un mensaje de éxito
        return redirect()->route('inventario')->with('success', 'Producto eliminado exitosamente.');

    }
}
