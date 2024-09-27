<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clientes;
class ClienteController
{

    //Función para mostrar la vista de nuevo-cliente
    public function index()
    {
        return view('pages.ventas.nuevo-cliente');
    }

    //Función para buscar un cliente en la base de datos
    public function buscarCliente(Request $request)
    {
        //Se obtiene el DUI del cliente del formulario en la vista
        $duiCliente = $request->input('duiCliente');
        //Se busca un cliente en la base de datos por medio de su DUI
        $cliente = clientes::where('DUI', $duiCliente)->first();
        //Se obtienen los productos totales y el total de la venta de la sesión
        $productosTotales = session('productosTotales', []);
        $totalVenta = session('total', 0);

        //Se almacena el cliente en la sesión
        session()->put('cliente', $cliente);

        //Se retorna la vista de factura con los productos totales, el total de la venta y el cliente
        return view('pages.ventas.factura', ['productos' => $productosTotales, 'total' => $totalVenta, 'cliente' => $cliente]);

    }

    //Función para almacenar un nuevo cliente en la base de datos
    public function store(Request $request)
    {
        //Se obtienen los datos del cliente del formulario en la vista
        $clienteData = $request->only('Nombres', 'Apellidos', 'DUI', 'Telefono', 'Email');
        //Se crea un nuevo cliente en la base de datos
        $cliente = clientes::create($clienteData);
        //Se obtienen los productos totales y el total de la venta de la sesión
        $productosTotales = session('productosTotales', []);
        $totalVenta = session('total', 0);

        //Se almacena el cliente en la sesión
        session(['cliente' => $cliente]);

        //Se retorna la vista de factura con los productos totales, el total de la venta y el cliente
        return view('pages.ventas.factura', ['productos' => $productosTotales, 'total' => $totalVenta, 'cliente' => $cliente]);
    }

}
