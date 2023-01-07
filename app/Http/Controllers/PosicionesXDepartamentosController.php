<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\PosicionesXDepartamentos;
use App\Models\DepartamentosXVicepresidencias;
use App\Models\DireccionesVicepresidencias;
use App\Models\EmpresasXGruposEmpresariales;

class PosicionesXDepartamentosController extends Controller
{
    //

    public function store(Request $request)
    {
        $validator = $request->validate([
            'departamento' => ['required'],
            'posiciones' => ['required'],
        ]);

        $nombre_posicion = $request->posiciones;

        $departamentos = $request->departamento;
        $empresa_arr = $request->empresa;
        $direccion_vp = $request->direccion_vp;

        if(count($nombre_posicion) > 0)
        {
            for($x = 0; $x < count($departamentos); $x++)
            {
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr[$x])->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp[$x]],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $departamentos[$x]],
                                                                ['cod_vicepresidencia', $vicep->cod_vicepresidencia]])->first();
                if(isset($depart->cod_departamento))
                {
                    //Eliminmos los datos para insertar de nuevo 
                    PosicionesXDepartamentos::where('cod_departamento', $depart->cod_departamento)->delete();
                }
            }
            
            for($x = 0; $x < count($nombre_posicion); $x++)
            {
                $dda_arr = explode('||', $nombre_posicion[$x]);
                $nombre_departamento = $dda_arr[0];

                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr[$x])->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp[$x]],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $nombre_departamento],
                                                                ['cod_vicepresidencia', $vicep->cod_vicepresidencia]])->first();
                if(isset($depart->cod_departamento))
                {
                    $data_in_posici = new PosicionesXDepartamentos();
                    $data_in_posici->cod_departamento = $depart->cod_departamento;
                    $data_in_posici->nombre_posicion = $dda_arr[1];
                    $data_in_posici->save();

                    //Actualizamos el registro de dato temporal en S
                    $data_temp = CargaDatos::where('posicion', $dda_arr[1])
                                            ->update(['validacion_posicon' => 'S',
                                                      'posicion_agregada' => 'S']);
                }
            }
        
            return redirect('admin/app/validacionDatos?vicp=4')
                        ->with(['message' => __('Position data uploaded successfully'), 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Failed to save positions'),
                                'alert' => 'danger']);
        }
    }
}
