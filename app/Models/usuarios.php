<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class usuarios extends Authenticatable
{
    use HasFactory;
    protected $table = 'usuarios';
    protected $primaryKey = 'idUsuarios';
    public $incrementing = true;
    protected $fillable = ['Nombre_Usuario', 'Contrasenia', 'ID_Rol'];
    public $timestamps = false;
    public function rol()
    {
        return $this->belongsTo('App\Models\roles', 'ID_Rol', 'idRoles');
    }
    public function getAuthPassword()
    {
        return $this->Contrasenia;
    }
}