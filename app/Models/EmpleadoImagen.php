<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoImagen extends Model
{
    use HasFactory;
    
    protected $table = 'empleado_imagen';
    
    protected $fillable = ['idempleado', 'name', 'myname', 'mimetype'];
    
    protected $attributes = ['name' => null, 'myname' => null, 'mimetype' => null];
    
    public function empleados () {
        return $this->hasMany('App\Models\Empleado', 'id');
    }
}
