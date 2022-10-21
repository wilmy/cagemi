<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\DireccionesVicepresidencias;
use App\Models\DepartamentosXVicepresidencias;

class DepartamentosXVicepresidenciasController extends Controller
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
            'departamentos' => ['required'],
            'direccion_vp' => ['required'],
        ]);

        $nombre_departamento = $request->departamentos;
        $direccion_vp = $request->direccion_vp;
        if(count($nombre_departamento) > 0)
        {
            for($x = 0; $x < count($direccion_vp); $x++)
            {
                $vicep = DireccionesVicepresidencias::where('nombre_vicepresidencia', $direccion_vp[$x])->first();

                if(isset($vicep->cod_vicepresidencia))
                {
                    //Eliminmos los datos para insertar de nuevo 
                    DepartamentosXVicepresidencias::where('cod_vicepresidencia', $vicep->cod_vicepresidencia)->delete();
                }
            }
            
            for($x = 0; $x < count($nombre_departamento); $x++)
            {
                $dda_arr = explode('||', $nombre_departamento[$x]);
                $nombre_vicepresidencia = $dda_arr[0];
                $vicep = DireccionesVicepresidencias::where('nombre_vicepresidencia', $nombre_vicepresidencia)->first();
                if(isset($vicep->cod_vicepresidencia))
                {
                    $data_in_depart = new DepartamentosXVicepresidencias();
                    $data_in_depart->cod_vicepresidencia = $vicep->cod_vicepresidencia;
                    $data_in_depart->nombre_departamento = $dda_arr[1];
                    $data_in_depart->save();

                    //Actualizamos el registro de dato temporal a S
                    $data_temp = CargaDatos::where('departamento', $dda_arr[1])
                                            ->update(['validacion_departamento' => 'S',
                                                      'departamento_agregado' => 'S']);
                }
            }
            
        
            return redirect('admin/app/validacionDatos?vicp=3')
                        ->with(['message' => __('Department data loaded correctly'), 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Error saving departments'),  
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
