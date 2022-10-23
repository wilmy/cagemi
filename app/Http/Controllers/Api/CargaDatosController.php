<?php

namespace App\Http\Controllers\Api;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CargaDatosController extends Controller
{
    public function index(Request $request)
    {
        if(isset($request->page))
        {
            $data = CargaDatos::paginate($request->page);
        }
        else
        {
            $data = CargaDatos::get();
        }

        return response()->json($data); 
    }

}
