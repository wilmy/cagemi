<?php

namespace App\Http\Controllers\Api;

use App\Models\User; 
use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmpresasXGruposEmpresariales; 

class EmpresasXGruposEmpresarialesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->page))
        {
            $data = EmpresasXGruposEmpresariales::where('estatus', 'A')->paginate($request->page);
        }
        else
        {
            $data = EmpresasXGruposEmpresariales::where('estatus', 'A')->get();
        }

        return response()->json($data); 
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
            'empresas' => ['required'],
        ]);

        $nombre_empresas = $request->empresas;
        if(count($nombre_empresas) > 0)
        {
            //Eliminmos los datos para insertar de nuevo las nuevas empresas o existentes
            EmpresasXGruposEmpresariales::where('cod_grupo_empresarial', auth()->user()->cod_grupo_empresarial)->delete();

            for($x = 0; $x < count($nombre_empresas); $x++)
            {
                $data_in_empresa = new EmpresasXGruposEmpresariales();
                $data_in_empresa->cod_grupo_empresarial = auth()->user()->cod_grupo_empresarial;
                $data_in_empresa->nombre = $nombre_empresas[$x];
                $data_in_empresa->save();

                //Actualizamos el registro de dato temporal de empresa
                $data_temp = CargaDatos::where('empresa', $nombre_empresas[$x])
                                        ->update(['validacion_empresa' => 'S',
                                                   'empresa_agregada' => 'S']);
                
            }
        
            return redirect('admin/app/validacionDatos?vicp=1')
                        ->with(['message' => __('Company data loaded correctly'), 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Error saving companies'), 
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
