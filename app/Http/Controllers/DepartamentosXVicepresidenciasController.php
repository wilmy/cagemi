<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $nombre_departamento = $request->departamentos;
        if(count($nombre_departamento) > 0)
        {
            $cod_vicepresidencia = 1;
            //Eliminmos los datos para insertar de nuevo las nuevas empresas o existentes
            DepartamentosXVicepresidencias::where('cod_vicepresidencia', $cod_vicepresidencia)->delete();

            for($x = 0; $x < count($nombre_departamento); $x++)
            {
                $data_in_vicepre = new DepartamentosXVicepresidencias();
                $data_in_vicepre->cod_vicepresidencia = $cod_vicepresidencia;
                $data_in_vicepre->nombre_departamento = $nombre_departamento[$x];
                $data_in_vicepre->save();
            }
        
            return redirect('admin/app/validacionDatos?vicp=3')
                        ->with(['message' => 'Datos de departamentos cargados correctamente', 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => 'Error al guardar las departamentos', 
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
