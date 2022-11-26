<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CargaDatos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\EmpleadosXPosicion;
use App\Models\PosicionesXDepartamentos;

class EmpleadosXPosicionController extends Controller
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
            'empleados' => ['required'],
            'posiciones' => ['required'],
        ]);

        $posiciones = $request->posiciones;
        $empledos = $request->empleados;
        if(count($empledos) > 0)
        {
            for($x = 0; $x < count($posiciones); $x++)
            {
                $depart = PosicionesXDepartamentos::where('nombre_posicion', $posiciones[$x])->first();
                if(isset($depart->cod_departamento))
                {
                    //Eliminmos los datos para insertar de nuevo 
                    PosicionesXDepartamentos::where('cod_departamento', $depart->cod_departamento)->delete();
                }
            }
            
            for($x = 0; $x < count($empledos); $x++)
            {
                $dda_arr                = explode('||', $empledos[$x]);
                $nombre_posicion        = $dda_arr[0];
                $cod_supervisor         = $dda_arr[1];
                $nombres                = $dda_arr[2];
                $apellidos              = $dda_arr[3];
                $documento              = trim($dda_arr[4]);
                $extencion              = $dda_arr[5];
                $correo_instutucional   = $dda_arr[6];
                $correo_personal        = $dda_arr[7];
                $cod_empleado           = $dda_arr[8];
                $fecha_nacimiento       = $dda_arr[9];
                $telefono_movil         = $dda_arr[10];
                $telefono_institucional = $dda_arr[11];
                $estatus                = 'A';
                $activo_hasta           = date('Y-m-d H:i:s');
                
                $posicion = PosicionesXDepartamentos::where('nombre_posicion', $nombre_posicion)->first();
                if(isset($posicion->cod_posicion))
                {
                    $data_in_emplead = new EmpleadosXPosicion();
                    $data_in_emplead->cod_empleado_empresa      = $cod_empleado;
                    $data_in_emplead->cod_posicion              = $posicion->cod_posicion;
                    $data_in_emplead->nombres                   = $nombres;
                    $data_in_emplead->apellidos                 = $apellidos;
                    $data_in_emplead->estatus                   = $estatus;
                    $data_in_emplead->documento                 = $documento;

                    $this->validateValue($data_in_emplead, 'cod_supervisor', $cod_supervisor);
                    $this->validateValue($data_in_emplead, 'telefono_institucional', $telefono_institucional);
                    $this->validateValue($data_in_emplead, 'extencion', $extencion);
                    $this->validateValue($data_in_emplead, 'correo_institucional', $correo_instutucional);
                    $this->validateValue($data_in_emplead, 'correo_personal', $correo_personal);
                    $this->validateValue($data_in_emplead, 'activo_hasta', $activo_hasta);
                    $this->validateValue($data_in_emplead, 'fecha_nacimiento', $fecha_nacimiento);
                    $this->validateValue($data_in_emplead, 'telefono_movil', $telefono_movil);

                    $data_in_emplead->save();

                    //Actualizamos el registro de dato temporal a S
                    $data_temp = CargaDatos::where('documento', $documento)
                                            ->update(['validacion_empleados' => 'S',
                                                      'empleado_agregado' => 'S']);

                    //Registro de usuarios 
                    $cod_grupo_empresarial =  auth()->user()->cod_grupo_empresarial;
                    $user = User::create([
                        'cod_grupo_empresarial' => $cod_grupo_empresarial,
                        'name' => $nombres,
                        'cod_empleado' => $cod_empleado,
                        'password' => Hash::make($documento),
                        'estatus' => 'A'
                    ]);
                }
            }
        
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Employee data uploaded successfully'),
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Error saving employees'), 
                                'alert' => 'danger']);
        }
    }

    public function validateValue($model, $input, $dataImput)
    {
        if($dataImput != '')
        {
            $model->$input = $dataImput;
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
