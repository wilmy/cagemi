<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametros extends Model
{
    use HasFactory;
    protected $table = 'tb_parametros';
    protected $fillable = [
        'cod_parametro',
        'cod_grupo_empresarial',
        'parametro',
        'valor', 
        'estatus'
    ];
}
