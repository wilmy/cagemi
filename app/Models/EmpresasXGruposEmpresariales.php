<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresasXGruposEmpresariales extends Model
{
    use HasFactory;
    protected $table = 'tb_empresas_x_grupos_empresariales';
    protected $primaryKey = 'cod_empresa';
    protected $fillable = [
        'cod_empresa',
        'cod_grupo_empresarial',
        'nombre',
        'cod_pais',
        'estatus', 
        'logo'
    ];
}
