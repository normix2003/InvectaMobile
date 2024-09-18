<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorias extends Model
{
    use HasFactory;
    protected $table = 'categorias';
    protected $primaryKey = 'idCategorias';
    public $incrementing = true;
    protected $fillable = ['Nombre_Categoria', ' Descripcion'];
    public $timestamps = false;
}
