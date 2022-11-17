<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TiposPublicaciones;
use App\Http\Controllers\Controller;

class TiposPublicacionesController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = TiposPublicaciones::where('estatus', 'A')->get();

        return response()->json($data); 
    }
}
