<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadosXDepartamentos extends Model
{
    use HasFactory;
    protected $table = 'tb_empleados_x_departamentos';
    protected $primaryKey = 'cod_empleado';
    protected $fillable = [
        'cod_posicion',
        'cod_supervisor',
        'nombres',
        'apellidos',
        'documento', 
        'telefono_movil', 
        'extencion', 
        'correo_instutucional', 
        'correo_personal', 
        'foto', 
        'estatus', 
        'activo_hasta'
    ];
}
