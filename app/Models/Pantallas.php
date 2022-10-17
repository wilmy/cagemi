<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pantallas extends Model
{
    use HasFactory;

    protected $table = 'pantallas';
    protected $primaryKey = 'cod_pantalla';
    protected $fillable = [
        'cod_pantalla',
        'id_padre',
        'nombre',
        'slug', 
        'url',
        'descripcion',
        'orden',
        'icono',
        'ver',
        'crear',
        'editar',
        'eliminar',
        'estatus',
    ];
}
