<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detallesventas extends Model
{
    use HasFactory;
    protected $table = "detalles_ventas";
    protected $primaryKey = "idDetalles_Ventas";
    public $incrementing = true;
    protected $fillable = ['ID_Venta', 'ID_Productos', 'Cantidad', 'Precio_Unitario', 'Subtotal'];
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(producto::class, 'ID_Productos', 'idProductos');
    }

    public function venta()
    {
        return $this->belongsTo(ventas::class, 'ID_Venta', 'idVentas');
    }

}
