<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionesVicepresidencias extends Model
{
    use HasFactory;
    protected $table = 'tb_vicepresidencia_x_empresa';
    protected $primaryKey = 'cod_vicepresidencia';
    protected $fillable = [
        'cod_empresa',
        'nombre_vicepresidencia',
    ];

    public function empresa()
    {
        return $this->belonsTo(EmpresasXGruposEmpresariales::class, 'cod_empresa');
    }
}
