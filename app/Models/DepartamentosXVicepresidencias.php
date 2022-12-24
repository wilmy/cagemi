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

    public $cod_departamento;
    public $cod_vicepresidencia;
    public $nombre_departamento;
    public $created_at;
    public $updated_at;

    public function __construct($cod_departamento, $cod_vicepresidencia, $nombre_departamento, $created_at, $updated_at)
    {
        $this->cod_departamento = $cod_departamento;
        $this->cod_vicepresidencia = $cod_vicepresidencia;
        $this->nombre_departamento = $nombre_departamento;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function vicepresidencia()
    {
        return $this->belongsTo(DireccionesVicepresidencias::class, 'cod_vicepresidencia');
    }
}
