<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DepartamentosXVicepresidenciasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DepartamentosXVicepresidenciasApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $grupo = Crypt::decrypt($request->grupo, false, env('APP_ENCRYPTION'));
        $data = "";
        if (isset($request->cod_vicepresidencia)) {
            $depaController = new DepartamentosXVicepresidenciasController();
            $data = $depaController->getDepartamentos($request->cod_vicepresidencia,$grupo );
        }        
        
        return response()->json($data); 

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}