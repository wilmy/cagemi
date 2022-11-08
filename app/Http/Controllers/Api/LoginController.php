<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\EmpleadosXPosicion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    /*Zaret121519*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();
        $dataUser = DB::table('users')
                        ->leftjoin('tb_empleados_x_posicion', 'users.cod_empleado', '=','tb_empleados_x_posicion.cod_empleado_empresa')
                        ->where([
                                ['users.cod_grupo_empresarial', '=', $request->empresa],
                                ['users.cod_empleado', '=', $request->user],
                                //['users.password', '=', Hash::make($request->password)]
                            ])
                        ->select('tb_empleados_x_posicion.*')
                        ->get();

        if(count($dataUser) > 0)
        {
            array_push($data, array("estatus" => 'success', "dataUser" => $dataUser));
        }
        else
        {
            array_push($data, array("message" => "Error, usuario o contraseña incotecta."));
        }

        return response()->json($data); 
    }

    public function completarDatos(Request $request)
    { 
        $data = array();
       
        $cod_empleado            = $request->cod_empleado;
        $correo_personal         = (isset($request->correo_personal) ? $request->correo_personal : '');  
        $fechaNacimiento         = (isset($request->fecha_nacimiento) ? $request->fecha_nacimiento : '');
        $telefonoCelular         = (isset($request->telefono_movil) ? $request->telefono_movil : '');
        $telefonoInstitucional   = (isset($request->telefono_institucional) ? $request->telefono_institucional : '');
        $extencion               = (isset($request->extencion) ? $request->extencion : '');

        if(empty($telefonoInstitucional) && 
            empty($extencion) && 
            empty($correo_personal) && 
            empty($fechaNacimiento)&& 
            empty($telefonoCelular))
        {
            array_push($data, array("estatus" => 'error', "message" => "No hay datos para actualizar"));
        }
        else
        {
            $data_in_emplead = EmpleadosXPosicion::find($cod_empleado);
            $fechaNacimiento_F = substr($fechaNacimiento, 6,4).'-'.substr($fechaNacimiento, 3,2).'-'.substr($fechaNacimiento, 0,2);
            $this->validateValue($data_in_emplead, 'telefono_institucional', $telefonoInstitucional);
            $this->validateValue($data_in_emplead, 'extencion', $extencion);
            $this->validateValue($data_in_emplead, 'correo_personal', $correo_personal);
            $this->validateValue($data_in_emplead, 'fecha_nacimiento', $fechaNacimiento_F);
            $this->validateValue($data_in_emplead, 'telefono_movil', $telefonoCelular);

            if($data_in_emplead->save())
            {
                array_push($data, array("estatus" => 'success', "message" => "Datos actualizados correctamente"));
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error, al actualizar los datos."));
            }
        }

        return response()->json($data); 
    }

    public function validateValue($model, $input, $dataImput)
    {
        if($dataImput != '')
        {
            $model->$input = $dataImput;
        }
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