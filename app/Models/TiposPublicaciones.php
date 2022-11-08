<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposPublicaciones extends Model
{
    use HasFactory;
    protected $table = 'tb_tipo_publicaciones';
    protected $primaryKey = 'cod_tipo_publicacion';
    protected $fillable = [
        'cod_tipo_publicacion',
        'nombre',
        'icono',
        'estatus'
    ];
}
