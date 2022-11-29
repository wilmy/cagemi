<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicaciones extends Model
{
    use HasFactory;
    protected $table = 'tb_publicaciones';
    protected $primaryKey = 'cod_publicacion';
    protected $fillable = [
        'cod_publicacion',
        'cod_padre_publicacion',
        'cod_usuario',
        'cod_comunidad',
        'cod_tipo_publicacion', 
        'texto', 
        'permite_reaccion', 
        'permite_comentario', 
        'estatus'
    ];
}
