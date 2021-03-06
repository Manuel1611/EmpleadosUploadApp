<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;
    
    protected $table = 'puesto';
    
    protected $fillable = ['name', 'minsal', 'maxsal'];
    
    protected $attributes = ['minsal' => 965];
    
    public function empleados () {
        return $this->hasMany('App\Models\Empleado', 'idpuesto');
    }
    
}
