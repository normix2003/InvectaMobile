<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $primaryKey = 'idClientes';
    public $incrementing = true;
    protected $fillable = ['Nombres', 'Apellidos', 'Email', 'Telefono', 'DUI'];
    public $timestamps = false;
}
