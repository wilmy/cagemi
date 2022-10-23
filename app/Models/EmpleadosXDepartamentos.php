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
        'telefono_institucional', 
        'extencion', 
        'correo_institucional', 
        'correo_personal', 
        'foto',  
        'fecha_nacimiento', 
        'estatus', 
        'activo_hasta'
    ];
}
