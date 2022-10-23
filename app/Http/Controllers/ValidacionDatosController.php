<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Http\Controllers\CargaDatosController;

class ValidacionDatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_empresas = CargaDatos::where([['validacion_empresa', 'N'], ['empresa','<>', '']])->get()->unique('empresa');
        $data_direcciones_vicepre = CargaDatos::where([['validacion_VP', 'N'],['empresa_agregada', 'S'], ['direccion_vp','<>', '']])
                                                ->select('direccion_vp', 'empresa')
                                                ->groupByRaw('direccion_vp, empresa')
                                                ->orderBy('empresa', 'ASC')
                                                ->get();

        $data_departamentos = CargaDatos::where([['validacion_departamento', 'N'],['vicepresidencia_agregada', 'S'], ['departamento','<>', '']])
                                        ->select('departamento', 'direccion_vp', 'empresa')
                                        ->groupByRaw('departamento, direccion_vp, empresa')
                                        ->orderBy('direccion_vp', 'ASC')
                                        ->get();

        $data_posiciones = CargaDatos::where([['validacion_posicon', 'N'],['departamento_agregado', 'S'],['posicion','<>', '']])
                                        ->select('posicion', 'departamento', 'direccion_vp', 'empresa')
                                        ->groupByRaw('posicion, departamento, direccion_vp, empresa')
                                        ->orderBy('departamento', 'ASC')
                                        ->get();

        $data_empleados = CargaDatos::where([['validacion_empleados', 'N'],['empresa_agregada', 'S'],['vicepresidencia_agregada', 'S'],['departamento_agregado', 'S'],['posicion_agregada', 'S']])
                                    ->orderBy('posicion', 'ASC')
                                    ->get();

        $validacionesDataTmp = CargaDatosController::validacionesDataTmp();
        
        return view('/content/apps/validacionDatos/index', 
                    [
                        'data_empresas' => $data_empresas,
                        'data_direcciones_vicepre' => $data_direcciones_vicepre,
                        'data_departamentos' => $data_departamentos,
                        'data_posiciones' => $data_posiciones,
                        'data_empleados' => $data_empleados,
                        'validacionesDataTmp' => $validacionesDataTmp
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
