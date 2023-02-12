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

class UsuariosXEmpresaController extends Controller
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
                        ->leftjoin('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=','tb_posiciones_x_departamento.cod_posicion')
                        ->where([
                                ['users.cod_grupo_empresarial', '=', $request->cod_grupo_empresarial]
                            ])
                        ->select('tb_empleados_x_posicion.*', 
                                 'tb_posiciones_x_departamento.nombre_posicion', 
                                 'users.token_autentication', 
                                 'users.password', 
                                 'users.cambio_password', 
                                 'users.id', 
                                 'users.cod_grupo_empresarial', 
                                 'users.profile_photo_path', 
                                 'users.email_verified_at')
                        ->paginate(20);
        if(count($dataUser) > 0)
        {
            array_push($data, array("estatus" => 'success', 
                                    "dataUser" => $dataUser));
        }
        else
        {
            array_push($data, array("message" => "Error en la busqueda."));
        }

        return response()->json($data); 
    }
}
