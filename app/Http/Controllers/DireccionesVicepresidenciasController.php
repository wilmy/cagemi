<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\DireccionesVicepresidencias;
use App\Models\EmpresasXGruposEmpresariales;

class DireccionesVicepresidenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator = $request->validate([
            'vicepresidencias' => ['required'],
            'empresa' => ['required'],
        ]);

        $nombre_vicepresidencias = $request->vicepresidencias;
        $empresa_nombre = $request->empresa;
        if(count($nombre_vicepresidencias) > 0)
        {
            for($x = 0; $x < count($empresa_nombre); $x++)
            {
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_nombre[$x])->first();
                if(isset($empresa->cod_empresa))
                {
                    //Eliminmos los datos para insertar de nuevo las nuevas empresas o existentes
                    DireccionesVicepresidencias::where('cod_empresa', $empresa->cod_empresa)->delete();
                }
            }
            
            for($x = 0; $x < count($nombre_vicepresidencias); $x++)
            {
                $dda_arr = explode('||', $nombre_vicepresidencias[$x]);
                $nombre_empresa = $dda_arr[0];
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $nombre_empresa)->first();
                if(isset($empresa->cod_empresa))
                {
                    $data_in_vicepre = new DireccionesVicepresidencias();
                    $data_in_vicepre->cod_empresa = $empresa->cod_empresa;
                    $data_in_vicepre->nombre_vicepresidencia = $dda_arr[1];
                    $data_in_vicepre->save();

                     //Actualizamos el registro de dato temporal a S
                     $data_temp = CargaDatos::where('direccion_vp', $dda_arr[1])
                                            ->update(['validacion_VP' => 'S',
                                                      'vicepresidencia_agregada' => 'S']);
                }
            }
        
            return redirect('admin/app/validacionDatos?vicp=2')
                        ->with(['message' => 'Datos de vicepresidencia cargados correctamente', 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => 'Error al guardar las vicepresidencias', 
                                'alert' => 'danger']);
        }
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
