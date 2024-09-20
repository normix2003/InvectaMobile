<?php

namespace App\Http\Controllers;

use App\Models\detallesventas;
use App\Models\empleados;
use App\Models\producto;
use App\Models\clientes;
use App\Models\ventas;
use Auth;
use Illuminate\Http\Request;

class VentasController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = session('productosBuscados', []);

        return view('pages.ventas.ventas', ['productos' => $productos]);
    }

    public function buscarProducto(Request $request)
    {

        $data = $request->input('Data');
        //dd($data);
        $producto = producto::with(['marca', 'categoria'])
            ->where('Nombre_Producto', 'like', '%' . $data . '%')
            ->orWhereHas('marca', function ($q) use ($data) {
                $q->where('Nombre_Marca', 'like', '%' . $data . '%');
            })
            ->orWhereHas('categoria', function ($q) use ($data) {
                $q->where('Nombre_Categoria', 'like', '%' . $data . '%');
            })
            ->get();
        session()->put('productosBuscados', $producto);
        return redirect()->route('ventas');
        //return view('pages.ventas.ventas', ['productos' => $producto]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function factura(Request $request)
    {
        $productosSeleccionados = $request->input('productos');
        //dd($productosSeleccionados);
        $productosSeleccionados = json_decode($productosSeleccionados, true);

        $totalVenta = 0;
        $productosTotales = [];
        foreach ($productosSeleccionados as $producto) {
            $productosTotales[] = [
                'idProductos' => $producto['id'],
                'Nombre_Producto' => $producto['nombre'],
                'Cantidad' => $producto['cantidad'],
                'Precio' => $producto['precio'],
                'Total' => number_format((float) ($producto['cantidad'] * $producto['precio']), 2)
            ];
            $totalVenta += $producto['precio'] * $producto['cantidad'];
        }

        session()->forget('productosBuscados');
        session()->put('productosTotales', $productosTotales);
        session()->put('total', $totalVenta);
        //$cliente = session('cliente', []);

        return view('pages.ventas.factura', ['productos' => $productosTotales, 'total' => $totalVenta, 'cliente' => []]);
    }
    public function nuevoCliente()
    {
        return view('pages.ventas.nuevo-cliente');
    }
    public function clienteStore(Request $request)
    {
        $clienteData = $request->only('Nombres', 'Apellidos', 'DUI', 'Telefono', 'Email');
        $cliente = clientes::create($clienteData);
        $productosTotales = session('productosTotales', []);
        $totalVenta = session('total', 0);

        session(['cliente' => $cliente]);

        return view('pages.ventas.factura', ['productos' => $productosTotales, 'total' => $totalVenta, 'cliente' => $cliente]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function finalizarVenta(Request $request)
    {
        $cliente = $request->input('idCliente');
        $productosTotales = $request->input('productos');
        $productos = json_decode($productosTotales, true);
        $totalVenta = $request->input('total');

        //dd($cliente, $productosTotales, $totalVenta);
        $usuario = Auth::user();

        //dd($usuario->idUsuarios);
        $empleado = empleados::where('ID_Usuarios', $usuario->idUsuarios)->first();
        //dd($empleado);
        if ($empleado) {
            $idEmpleado = $empleado->idEmpleados;
        }
        //dd($empleado);
        $ventas = ventas::create([
            'ID_Cliente' => $cliente,
            'ID_Empleado' => $idEmpleado,
            'Fecha' => date('Y-m-d'),
            'Total' => $totalVenta
        ]);

        foreach ($productos as $producto) {

            detallesventas::create([
                'ID_Venta' => $ventas->idVentas,
                'ID_Productos' => $producto['idProductos'],
                'Cantidad' => $producto['Cantidad'],
                'Precio_Unitario' => $producto['Precio'],
                'Subtotal' => $producto['Total']
            ]);
            $productoUpdate = producto::find($producto['idProductos']);
            $productoUpdate->Stock = $productoUpdate->Stock - $producto['Cantidad'];
            $productoUpdate->save();
        }
        session()->flash('status', 'Venta realizada exitosamente.');

        return redirect()->route('ventas');

    }

    /**
     * Display the specified resource.
     */
    public function buscarCliente(Request $request)
    {
        $duiCliente = $request->input('duiCliente');
        $cliente = clientes::where('DUI', $duiCliente)->first();
        $productosTotales = session('productosTotales', []);
        $totalVenta = session('total', 0);
        session()->put('cliente', $cliente);
        return view('pages.ventas.factura', ['productos' => $productosTotales, 'total' => $totalVenta, 'cliente' => $cliente]);

    }

    public function detallesVentas()
    {
        $ventas = detallesventas::with(['producto', 'venta.empleado', 'venta.cliente'])->get();

        //dd($ventas);
        return view('pages.ventas.detalles-ventas', ['ventas' => $ventas]);
    }
}
