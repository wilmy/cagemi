<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentariosXPublicaciones extends Model
{
    use HasFactory;

    protected $table = 'tb_comentarios_x_publicaciones';
    protected $primaryKey = 'cod_comentario';
    protected $fillable = [
        'cod_comentario',
        'cod_publicacion',
        'cod_usuario',
        'comentario'
    ];
}
