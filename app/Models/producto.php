<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class producto extends Model
{
    use HasFactory;
    protected $table = 'productos';
    protected $primaryKey = 'idProductos';
    public $incrementing = true;
    protected $fillable = ['Nombre_Producto', 'ID_Marca', 'ID_Categoria', 'Descripcion', 'Precio', 'Cantidad', 'Eliminar'];
    public $timestamps = false;

    public function marca()
    {
        return $this->belongsTo('App\Models\marcas', 'ID_Marca', 'idMarcas');
    }
    public function categoria()
    {
        return $this->belongsTo('App\Models\categorias', 'ID_Categoria', 'idCategorias');
    }
}
