<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detallesroles extends Model
{
    use HasFactory;
    protected $table = 'detalles_roles';
    protected $primaryKey = 'idDetalles_Roles';
    public $incrementing = true;
    protected $fillable = ['ID_Roles', 'ID_Permisos', 'Eliminar'];
    public $timestamps = false;

    public function rol()
    {
        return $this->belongsTo('App\Models\roles', 'ID_Roles', 'idRoles');
    }
    public function permisos()
    {
        return $this->belongsTo('App\Models\permisos', 'ID_Permisos', 'idPermisos');
    }
}
