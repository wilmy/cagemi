<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoEmpresarial extends Model
{
    use HasFactory;

    protected $table = 'tb_grupos_empresariales';
    protected $primaryKey = 'cod_grupo_empresarial';
    protected $fillable = [
        'cod_grupo_empresarial',
        'nombre',
        'estatus', 
        'logo'
    ];

    public function parametros()
    {
        return $this->hasMany(Parametros::class, 'cod_grupo_empresarial', 'cod_grupo_empresarial');
    }
}
