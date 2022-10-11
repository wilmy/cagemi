<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pantallas extends Model
{
    use HasFactory;

    protected $table = 'pantallas';
    protected $fillable = [
        'id',
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
