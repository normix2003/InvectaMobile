<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    use HasFactory;
    protected $primaryKey = 'idRoles';
    public $incrementing = true;
    protected $table = 'roles';
    public $timestamps = false;
    protected $fillable = ['Nombre'];
    public function detallesroles()
    {
        return $this->hasMany('App\Models\detallesroles', 'ID_Roles', 'idRoles');
    }
}
