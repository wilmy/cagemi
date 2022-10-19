<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DireccionesVicepresidencias;

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
        $nombre_vicepresidencias = $request->vicepresidencias;
        if(count($nombre_vicepresidencias) > 0)
        {
            $cod_empresa = 1;
            //Eliminmos los datos para insertar de nuevo las nuevas empresas o existentes
            DireccionesVicepresidencias::where('cod_empresa', $cod_empresa)->delete();

            for($x = 0; $x < count($nombre_vicepresidencias); $x++)
            {
                $data_in_vicepre = new DireccionesVicepresidencias();
                $data_in_vicepre->cod_empresa = $cod_empresa;
                $data_in_vicepre->nombre_vicepresidencia = $nombre_vicepresidencias[$x];
                $data_in_vicepre->save();
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
