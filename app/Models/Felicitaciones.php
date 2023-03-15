<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Felicitaciones extends Model
{
    use HasFactory;

    protected $table = 'tb_felicitaciones'; 
    protected $fillable = [
        'id',
        'id_usuario',
        'id_usuario_felicitaciones', 
        'fecha_felicitaciones'
    ];

}
