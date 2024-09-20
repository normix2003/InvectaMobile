<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ventas extends Model
{
    use HasFactory;
    protected $table = "ventas";
    protected $primaryKey = "idVentas";
    public $incrementing = true;

    protected $fillable = ['ID_Cliente', 'ID_Empleado', 'Fecha', 'Total'];
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'ID_Cliente', 'idClientes');
    }
    public function empleado()
    {
        return $this->belongsTo(empleados::class, 'ID_Empleado', 'idEmpleados');
    }

}
