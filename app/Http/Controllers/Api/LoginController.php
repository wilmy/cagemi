<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\CargaDatos;
use Illuminate\Support\Str;
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
        $token = (isset($request->token) ? $request->token  : '');
        $data = array();
        $dataUser = DB::table('users as u')
                        ->join('tb_empleados_x_posicion as p', 'u.cod_empleado', '=','p.cod_empleado_empresa')
                        ->join('tb_posiciones_x_departamento as pd', 'p.cod_posicion', '=','pd.cod_posicion')
                        ->join('tb_departamentos_x_vicepresidencia as dv', 'dv.cod_departamento', '=','pd.cod_departamento')
                        ->join('tb_vicepresidencia_x_empresa as v', 'v.cod_vicepresidencia', '=','dv.cod_vicepresidencia')
                        ->where([
                                ['v.cod_empresa', '=', $request->empresa],
                                ['u.cod_empleado', '=', $request->user]
                            ])
                        ->select('p.*', 
                                 'pd.nombre_posicion', 
                                 'u.token_autentication',  
                                 'u.token_app', 
                                 'u.password', 
                                 'u.cambio_password', 
                                 'u.id', 
                                 'u.cod_grupo_empresarial', 
                                 'u.email_verified_at')
                        ->first();
        if($dataUser)
        {
            if(Hash::check($request->password, $dataUser->password))
            {
                $completar_datos = false;
                $cambio_password = $dataUser->cambio_password;

                if($dataUser->correo_personal == '' ||
                    $dataUser->fecha_nacimiento == '' ||
                    $dataUser->telefono_movil  == '' ||
                    $dataUser->telefono_institucional == '' ||
                    $dataUser->extencion == '') 
                { 
                    $completar_datos = true;
                }

                array_push($data, array("estatus" => 'success',
                                        'cambio_pass' => $cambio_password,
                                        'completar_datos' => $completar_datos,
                                        "dataUser" => $dataUser));
            }
            else if(($token == $dataUser->token_autentication))
            {
                $completar_datos = false;
                $cambio_password = $dataUser->cambio_password;

                if($dataUser->correo_personal == '' ||
                    $dataUser->fecha_nacimiento == '' ||
                    $dataUser->telefono_movil  == '' ||
                    $dataUser->telefono_institucional == '' ||
                    $dataUser->extencion == '') 
                { 
                    $completar_datos = true;
                }

                array_push($data, array("estatus" => 'success',
                                        'cambio_pass' => $cambio_password,
                                        'completar_datos' => $completar_datos,
                                        "dataUser" => $dataUser));
            }
            else
            {
                array_push($data, array("message" => "Usuario o contraseña incotecta."));
            }
        }
        else
        {
            array_push($data, array("message" => "Usuario o contraseña incotecta."));
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

            $feccha_conter = explode('/',$fechaNacimiento);
            $fechaNacimiento_F = ($fechaNacimiento != '' ? $feccha_conter[2].'-'.$feccha_conter[1].'-'.$feccha_conter[0] : '');
            
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

    public function cambioPassword(Request $request)
    { 
        $data = array();
       
        $id                      = (isset($request->id) ? $request->id : '');  
        $cod_empleado            = (isset($request->cod_empleado) ? $request->cod_empleado : '');  
        $empresa                 = (isset($request->empresa) ? $request->empresa : '');
        $password                = (isset($request->password) ? $request->password : '');
        $confirmPassword         = (isset($request->confirmPassword) ? $request->confirmPassword : '');

        if(empty($id) && 
            empty($cod_empleado) && 
            empty($empresa) && 
            empty($password) && 
            empty($confirmPassword))
        {
            array_push($data, array("estatus" => 'error', "message" => "No hay datos para actualizar"));
        }
        else if($password != $confirmPassword)
        {
            array_push($data, array("estatus" => 'error', "message" => "Password no coinciden"));
        }
        else
        {
            //$data_in_emplead = User::where([['cod_empleado', '=', $cod_empleado], ['cod_grupo_empresarial', '=', $empresa]]);
            $data_in_emplead = User::find($id);
            
            $this->validateValue($data_in_emplead, 'password', Hash::make($password));
            $this->validateValue($data_in_emplead, 'cambio_password', 'N');

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


    public function generate_token(Request $request)
    { 
        $data = array();
       
        $id_user = (isset($request->id_user) ? $request->id_user : '');

        if(empty($id_user))
        {
            array_push($data, array("estatus" => 'error', "message" => "No hay datos para generar el token"));
        }
        else
        {
            $data_in_emplead = User::find($id_user);
             
            $token = Str::uuid(); 
            $this->validateValue($data_in_emplead, 'token_autentication', $token);
            $mesas = "Token generado correctamente";
          

            if($data_in_emplead->save())
            {
               array_push($data, array("estatus" => 'success', "token"=> $token, "message" => $mesas));
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error, al generrar el token."));
            }
        }

        return response()->json($data); 
    }
    
    public function generate_token_app(Request $request)
    { 
        $data = array();
       
        $id_user = (isset($request->id_user) ? $request->id_user : '');   
        $token = (isset($request->token) ? $request->token : ''); 
        $tokenApp = (isset($request->tokenApp) ? $request->tokenApp : false); 
 
        if(empty($id_user))
        {
            array_push($data, array("estatus" => 'error', "message" => "No hay datos para generar el token"));
        }
        else
        {
            $data_in_emplead = User::find($id_user); 
            $data_in_emplead->token_app = $token; 

            if($data_in_emplead->save())
            {
               array_push($data, array("estatus" => 'success', "message" => "Token guardado correctamente"));
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error, al guardar el token."));
            }
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
