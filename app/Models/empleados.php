<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empleados extends Model
{
    use HasFactory;
    protected $table = 'empleados';
    protected $primaryKey = 'idEmpleados';
    public $incrementing = true;
    protected $fillable = ['Nombre_Empleado', 'Apellidos', 'Email', 'Telefono', 'DUI', 'ID_Usuarios'];
    public $timestamps = false;
    public function usuario()
    {
        return $this->belongsTo('App\Models\usuarios', 'ID_Usuarios', 'idUsuarios');
    }
}
