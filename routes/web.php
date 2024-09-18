<?php

use App\Http\Controllers\InventarioController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\VentasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LoginController;

Route::middleware(['auth', 'checkAdministrador'])->group(function () {

    Route::get('/nuevo-usuario', [UsuariosController::class, 'nuevoUsuario'])->name('nuevo-usuario');
    Route::post('/nuevo-usuario', [UsuariosController::class, 'store'])->name('usuario.store');
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
    Route::delete('/usuarios/{idEmpleados}', [UsuariosController::class, 'destroy'])->name('usuario.destroy');
    Route::get('/detalle-usuario/{idEmpleados}', [UsuariosController::class, 'detalleUsuario'])->name('detalle-usuario');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles.roles');
    Route::delete('/roles/{idRoles}', [RolesController::class, 'destroy'])->name('roles.destroy');
    Route::get('/nuevo-rol', [RolesController::class, 'nuevoRol'])->name('roles.nuevo-rol');
    Route::get('/detalle-roles/{idRoles}', [RolesController::class, 'detallesRoles'])->name('roles.detalles-roles');
    Route::post('/nuevo-rol', [RolesController::class, 'store'])->name('roles.store');

    Route::delete('/inventario/{idProductos}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
});


Route::middleware(['auth', 'checkPermisos:Ver'])->group(function () {
    Route::get('/home', function () {
        return view('pages.home');
    })->name('home');

    Route::get('/ventas', [VentasController::class, 'index'])->name('ventas');
    Route::get('/buscar-producto', [VentasController::class, 'buscarProducto'])->name('buscar-producto');

    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario');

});

Route::middleware(['auth', 'checkPermisos:Ver,Crear'])->group(function () {
    Route::post('/factura', [VentasController::class, 'factura'])->name('factura');
    Route::get('buscar-cliente', [VentasController::class, 'buscarCliente'])->name('buscar-cliente');
    Route::get('/nuevo-cliente', [VentasController::class, 'nuevoCliente'])->name('nuevo-cliente');
    Route::post('/nuevo-cliente', [VentasController::class, 'clienteStore'])->name('cliente.store');
    Route::post('/finalizar-venta', [VentasController::class, 'finalizarVenta'])->name('finalizar-venta');
});

Route::middleware(['auth', 'checkPermisos:Crear,Modificar'])->group(function () {

    Route::get('/nuevo-producto', [InventarioController::class, 'nuevoProducto'])->name('nuevo-producto');
    Route::post('/nuevo-producto', [InventarioController::class, 'store'])->name('nuevo-producto.store');

    Route::get('/nueva-marca', [InventarioController::class, 'nuevaMarca'])->name('inventario.marca');
    Route::post('/nueva-marca', [InventarioController::class, 'storeMarca'])->name('marca.store');

    Route::get('/nueva-categoria', [InventarioController::class, 'nuevaCategoria'])->name('inventario.categoria');
    Route::post('/nueva-categoria', [InventarioController::class, 'storeCategoria'])->name('categoria.store');

});
Route::middleware(['auth', 'checkPermisos:Modificar'])->group(function () {

    Route::get('/stock/{idProductos}', [InventarioController::class, 'stock'])->name('stock');

    Route::put('/stock/{idProductos}', [InventarioController::class, 'update'])->name('stock.update');

});




Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');
});

//Route::get('/', [MarcaController::class, 'index']);
//Route::post('/', [MarcaController::class, 'store']);
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/crear-admin', [LoginController::class, 'crearAdmin'])->name('login.crearAdmin');
Route::post('/', [LoginController::class, 'login'])->name('login.login');



