<?php

namespace App\Http\Controllers;

use App\Models\detallesventas;
use App\Models\empleados;
use App\Models\producto;
use App\Models\ventas;
use Auth;
use Illuminate\Http\Request;
use PDF;

class VentasController
{
    //Funcion para mostrar la vista de ventas
    public function index()
    {
        //Obtener los productos buscados en la sesion
        $data = session('searchData', null);

        // Si no hay criterio de búsqueda, retornar todos los productos paginados
        if ($data === null) {
            $productos = producto::where('Eliminar', 0)->paginate(4);
        } else {
            //Buscar un producto por el nombre del producto, nombre de la marca o nombre de la categoria
            $productos = producto::with(['marca', 'categoria'])
                ->where('Eliminar', 0)
                ->where(function ($query) use ($data) {
                    $query->where('Nombre_Producto', 'like', '%' . $data . '%')
                        ->orWhereHas('marca', function ($q) use ($data) {
                            $q->where('Nombre_Marca', 'like', '%' . $data . '%');
                        })
                        ->orWhereHas('categoria', function ($q) use ($data) {
                            $q->where('Nombre_Categoria', 'like', '%' . $data . '%');
                        });
                })
                ->paginate(4);
        }

        //Retornar la vista de ventas con los productos
        return view('pages.ventas.ventas', ['productos' => $productos]);
    }

    //Funcion para buscar un producto
    public function buscarProducto(Request $request)
    {
        // Obtener el dato a buscar (Nombre del producto, Nombre de la marca o Nombre de la categoría)
        $data = $request->input('Data');

        // Guardar el criterio de búsqueda en la sesión
        session()->put('searchData', $data);

        // Redireccionar a la vista de ventas
        return redirect()->route('ventas');
    }

    //Funcion para finalizar una venta
    public function finalizarVenta(Request $request)
    {
        //Obtener el id del cliente, los productos y el total de la venta
        $cliente = session('cliente', []);
        $productos = session('productosTotales', []);
        $totalVenta = session('total', 0);

        //Obtener el usuario logueado que es el empleado que realiza la venta
        $usuario = Auth::user();
        $empleado = empleados::where('ID_Usuarios', $usuario->idUsuarios)->first();

        //Si el empleado existe se obtiene su id
        if ($empleado) {
            $idEmpleado = $empleado->idEmpleados;
        }

        //Crear una venta
        $ventas = ventas::create([
            'ID_Cliente' => $cliente['idClientes'],
            'ID_Empleado' => $idEmpleado,
            'Fecha' => date('Y-m-d'),
            'Total' => (float) str_replace(',', '', $totalVenta)
        ]);

        //Crear los detalles de la venta
        foreach ($productos as $producto) {

            detallesventas::create([
                'ID_Venta' => $ventas->idVentas,
                'ID_Productos' => $producto['idProductos'],
                'Cantidad' => $producto['Cantidad'],
                'Precio_Unitario' => (float) str_replace('.', '', $producto['Precio']),
                'Subtotal' => (float) str_replace(',', '', $producto['Total'])
            ]);
            //Actualizar la cantidad de productos en la base de datos
            $productoUpdate = producto::find($producto['idProductos']);
            $productoUpdate->Cantidad = $productoUpdate->Cantidad - $producto['Cantidad'];
            $productoUpdate->save();
        }
        return redirect()->route('factura-pdf');
    }

    public function facturaPdf(Request $request)
    {

        $cliente = session('cliente', []);
        $productos = session('productosTotales', []);
        $totalVenta = session('total', 0);
        $fecha = date('d-m-Y');
        $usuario = Auth::user();
        $empleado = empleados::where('ID_Usuarios', $usuario->idUsuarios)->first();
        //dd($cliente, $productos, $totalVenta);
        $pdf = PDF::loadView('pages.ventas.reporte', ['cliente' => $cliente, 'productos' => $productos, 'total' => $totalVenta, 'fecha' => $fecha, 'empleado' => $empleado]);
        session()->flash('status', 'Venta realizada exitosamente.');
        session()->forget('productosTotales');
        session()->forget('total');
        session()->forget('cliente');
        session()->forget('searchData');
        return $pdf->stream('Factura' . '-' . $cliente['Nombres'] . '-' . $fecha . '' . '.pdf');
    }

    //Funcion para mostrar la vista de ventas realizadas
    public function detallesVentas(Request $request)
    {
        //Obtener el tiempo de busqueda
        $tiempoSelect = $request->query('tiempoSelect');
        $tiempo = $request->query('tiempo') ?? $tiempoSelect;
        //Obtener las ventas segun el tiempo de busqueda
        switch ($tiempo) {
            case 'Todo':
                //Obtener todas las ventas
                $ventas = detallesventas::with(['producto', 'venta.empleado', 'venta.cliente'])
                    ->paginate(6);
                break;
            case 'Semana':
                //Obtener las ventas de la semana
                $ventas = detallesventas::with(['producto', 'venta.empleado', 'venta.cliente'])
                    ->whereHas('venta', function ($query) {
                        $query->whereBetween('Fecha', [date('Y-m-d', strtotime('-7 days')), date('Y-m-d')]);
                    })
                    ->paginate(6);
                break;
            case 'Mes':
                //Obtener las ventas del mes
                $ventas = detallesventas::with(['producto', 'venta.empleado', 'venta.cliente'])
                    ->whereHas('venta', function ($query) {
                        $query->whereMonth('Fecha', date('m'));
                    })
                    ->paginate(6);
                break;

            case 'Trimestre':
                //Obtener las ventas del trimestre
                $ventas = detallesventas::with(['producto', 'venta.empleado', 'venta.cliente'])
                    ->whereHas('venta', function ($query) {
                        $query->whereBetween('Fecha', [date('Y-m-d', strtotime('-3 months')), date('Y-m-d')]);
                    })
                    ->paginate(6);
                break;
            case 'Anio':
                //Obtener las ventas del año
                $ventas = detallesventas::with(['producto', 'venta.empleado', 'venta.cliente'])
                    ->whereHas('venta', function ($query) {
                        $query->whereYear('Fecha', date('Y'));
                    })
                    ->paginate(6);
                break;
            default:
                //Obtener las ventas del dia
                $ventas = detallesventas::with(['producto', 'venta.empleado', 'venta.cliente'])
                    ->whereHas('venta', function ($query) {
                        $query->whereDate('Fecha', date('Y-m-d'));
                    })
                    ->paginate(6);
                break;
        }
        //Retornar la vista de ventas realizadas
        return view('pages.ventas.detalles-ventas', ['ventas' => $ventas]);
    }
}
