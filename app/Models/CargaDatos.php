<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaDatos extends Model
{
    use HasFactory;
    protected $table = 'tb_carga_datos_temp';
    protected $fillable = [
        'cod_grupo_empresarial',
        'empresa',
        'cod_empleado',
        'nombres',
        'apellidos',
        'posicion',
        'direccion_vp',
        'departamento',
        'telefono_movil',
        'telefono_institucional',
        'extencion',
        'correo_instutucional',
        'correo_personal',
        'documento',
        'fecha_nacimiento',
        'codigo_superfisor',
        'estautus',
        'validacion_empresa',
        'validacion_VP',
        'validacion_departamento',
        'validacion_posicon',
        'validacion_empleados',
        'empresa_agregada',
        'vicepresidencia_agregada',
        'departamento_agregado',
        'posicion_agregada',
        'empleado_agregado',
    ];

    protected $dates = [
        'fecha_nacimiento',
    ];
}
