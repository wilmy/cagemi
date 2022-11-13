<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReaccionesXPublicaciones extends Model
{
    use HasFactory;

    protected $table = 'tb_reaciones_x_publicaciones';
    protected $primaryKey = 'cod_reaccion';
    protected $fillable = [
        'cod_reaccion',
        'cod_publicacion',
        'cod_usuario'
    ];
}
