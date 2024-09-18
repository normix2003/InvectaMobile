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

}
