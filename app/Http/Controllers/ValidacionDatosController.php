<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;

class ValidacionDatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_empresas = CargaDatos::where('validacion_empresa', 'N')->get()->unique('empresa');
        $data_direcciones_vicepre = CargaDatos::where('validacion_VP', 'N')->get()->unique('direccion_vp');
        $data_departamentos = CargaDatos::where('validacion_departamento', 'N')->get()->unique('departamento');
        $data_posiciones = CargaDatos::where('validacion_posicon', 'N')->get()->unique('posicion');
        $data_empleados= CargaDatos::where('validacion_empleados', 'N')->get();
        
        return view('/content/apps/validacionDatos/index', 
                    [
                        'data_empresas' => $data_empresas,
                        'data_direcciones_vicepre' => $data_direcciones_vicepre,
                        'data_departamentos' => $data_departamentos,
                        'data_posiciones' => $data_posiciones,
                        'data_empleados' => $data_empleados
                    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
