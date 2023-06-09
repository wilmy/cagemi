<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultimediaXPublicaciones extends Model
{
    use HasFactory;

    protected $table = 'tb_multimedia_x_publicaciones';
    protected $primaryKey = 'cod_elemento';
    protected $fillable = [
        'cod_elemento',
        'cod_publicacion',
        'server',
        'nombre_archivo'
    ];
}
