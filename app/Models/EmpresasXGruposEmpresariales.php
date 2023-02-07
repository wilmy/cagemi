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

    public function departamentos()
    {
        return $this->hasManyThrough(
            DepartamentosXVicepresidencias::class,
            DireccionesVicepresidencias::class,
            'cod_empresa', // Foreign key on the DireccionesVicepresidencias table...
            'cod_vicepresidencia', // Foreign key on the DepartamentosXVicepresidencias table...
            'cod_empresa', // Local key on the EmpresasXGruposEmpresariales table...
            'cod_vicepresidencia' // Local key on the DireccionesVicepresidencias table...
        );
    }

    public function viceprecidencias()
    {
        return $this->hasMany(DireccionesVicepresidencias::class, 'cod_empresa', 'cod_empresa');
    }   
}
