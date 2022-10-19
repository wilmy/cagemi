<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentosXVicepresidencias extends Model
{
    use HasFactory;
    protected $table = 'tb_departamentos_x_vicepresidencia';
    protected $primaryKey = 'cod_departamento';
    protected $fillable = [
        'cod_vicepresidencia',
        'nombre_departamento',
    ];
}
