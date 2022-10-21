<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosicionesXDepartamentos extends Model
{
    use HasFactory;
    protected $table = 'tb_posiciones_x_departamento';
    protected $primaryKey = 'cod_posicion';
    protected $fillable = [
        'cod_departamento',
        'nombre_posicion',
    ];
}