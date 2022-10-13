<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoEmpresarial extends Model
{
    use HasFactory;
    protected $table = 'tb_grupos_empresariales';
    protected $fillable = [
        'cod_grupo_empresarial',
        'nombre',
        'estatus', 
        'logo'
    ];
}
