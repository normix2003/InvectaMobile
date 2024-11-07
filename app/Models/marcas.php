<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class marcas extends Model
{
    use HasFactory;
    protected $table = 'marcas';
    protected $primaryKey = 'idMarcas';
    public $incrementing = true;
    protected $fillable = ['Nombre_Marca', 'Eliminar'];
    public $timestamps = false;
}
