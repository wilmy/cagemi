<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\PosicionesXDepartamentos;
use App\Models\DepartamentosXVicepresidencias;

class PosicionesXDepartamentosController extends Controller
{
    //

    public function store(Request $request)
    {
        $validator = $request->validate([
            'departamento' => ['required'],
            'posiciones' => ['required'],
        ]);

        $departamentos = $request->departamento;
        $nombre_posicion = $request->posiciones;
        if(count($nombre_posicion) > 0)
        {
            for($x = 0; $x < count($departamentos); $x++)
            {
                $depart = DepartamentosXVicepresidencias::where('nombre_departamento', $departamentos[$x])->first();
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
                $depart = DepartamentosXVicepresidencias::where('nombre_departamento', $nombre_departamento)->first();
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
