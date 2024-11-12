<?php

namespace App\Http\Controllers;

use App\Models\producto;
use Illuminate\Http\Request;

class FacturaController
{
    // Función para mostrar la vista de factura con los productos seleccionados
    public function index()
    {
        // Se obtienen los productos totales, cliente y el total de la venta de la sesión
        $productosTotales = session('productosTotales', []);
        $totalVenta = session('total', 0);
        $cliente = session('cliente', []);
        // Se retorna la vista de factura con los productos totales, el total de la venta y el cliente
        return view('pages.ventas.factura', ['productos' => $productosTotales, 'total' => $totalVenta, 'cliente' => $cliente]);
    }

    // Función para obtener los productos seleccionados y mostrar la vista de factura
    public function nuevaFactura(Request $request)
    {
        // Se obtienen los productos seleccionados del formulario en la vista
        $productosSeleccionados = $request->input('productos');
        $productosSeleccionados = json_decode($productosSeleccionados, true);

        // Se instancian las variables de los productos totales y el total de la venta
        $totalVenta = 0;
        $productosTotales = [];

        // Se recorren los productos seleccionados para obtener los datos de cada producto
        foreach ($productosSeleccionados as $producto) {
            $productoBD = producto::find($producto['id']);

            if ($productoBD->Cantidad < $producto['cantidad']) {
                return back()->withErrors(['error' => 'No hay suficiente cantidad de ' . $productoBD->Nombre_Producto . ' en el inventario.']);
            }
            // Se almacenan los datos de cada producto en un array
            $productosTotales[] = [
                'idProductos' => $producto['id'],
                'Nombre_Producto' => $producto['nombre'],
                'Cantidad' => $producto['cantidad'],
                'Precio' => $producto['precio'],
                // Se calcula el total de cada producto
                'Total' => number_format((float) ($producto['cantidad'] * $producto['precio']), 2)
            ];
            // Se calcula el total de la venta
            $totalVenta += $producto['precio'] * $producto['cantidad'];
        }

        // Se eliminan los productos buscados de la sesión
        session()->forget('productosBuscados');
        // Se almacenan los productos totales y el total de la venta en la sesión
        session()->put('productosTotales', $productosTotales);
        session()->put('total', $totalVenta);
        //$cliente = session('cliente', []);
        // Se retorna la vista de factura con los productos totales, el clienteVacio y el total de la venta
        return view('pages.ventas.factura', ['productos' => $productosTotales, 'total' => $totalVenta, 'cliente' => []]);
    }
}
