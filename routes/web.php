<?php

use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LoginController;

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('pages.home');
    })->name('home');

    Route::get('/inventario', function () {
        return view('pages.inventario.inventario');
    })->name('inventario');

    Route::get('/nuevo-producto', function () {
        return view('pages.inventario.nuevo-producto');
    })->name('nuevo-producto');

    Route::get('/stock', function () {
        return view('pages.inventario.stock');
    })->name('stock');

    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');

    Route::get('/nuevo-usuario', [UsuariosController::class, 'nuevoUsuario'])->name('nuevo-usuario');

    Route::post('/nuevo-usuario', [UsuariosController::class, 'store'])->name('usuario.store');

    Route::delete('/usuarios/{idEmpleados}', [UsuariosController::class, 'destroy'])->name('usuario.destroy');

    Route::get('/detalle-usuario/{idEmpleados}', [UsuariosController::class, 'detalleUsuario'])->name('detalle-usuario');

    Route::get('/ventas', function () {
        return view('pages.ventas.ventas');
    })->name('ventas');

    Route::get('/factura', function () {
        return view('pages.ventas.factura');
    })->name('factura');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles.roles');
    Route::delete('/roles/{idRoles}', [RolesController::class, 'destroy'])->name('roles.destroy');
    Route::get('/nuevo-rol', [RolesController::class, 'nuevoRol'])->name('roles.nuevo-rol');
    Route::get('/detalle-roles/{idRoles}', [RolesController::class, 'detallesRoles'])->name('roles.detalles-roles');
    Route::post('/nuevo-rol', [RolesController::class, 'store'])->name('roles.store');
});
//Route::get('/', [MarcaController::class, 'index']);
//Route::post('/', [MarcaController::class, 'store']);
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/crear-admin', [LoginController::class, 'crearAdmin'])->name('login.crearAdmin');
Route::post('/', [LoginController::class, 'login'])->name('login.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');


