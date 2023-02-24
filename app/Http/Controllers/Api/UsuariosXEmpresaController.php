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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $pageLimit = (isset($request->pageLimit) ? $request->pageLimit : 15);
        $text = (isset($request->text) ? $request->text : '');
        $busq = (isset($request->busq) ? $request->busq : false);

        $data = array();
        if($busq)
        {
            $dataUser = DB::table('users')
                            ->leftjoin('tb_empleados_x_posicion', 'users.cod_empleado', '=','tb_empleados_x_posicion.cod_empleado_empresa')
                            ->leftjoin('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=','tb_posiciones_x_departamento.cod_posicion')
                            ->where([
                                    ['users.cod_grupo_empresarial', '=', $request->cod_grupo_empresarial],
                                    ['tb_empleados_x_posicion.nombres', 'LIKE', '%'.$text.'%']
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
                            ->orderBy('tb_empleados_x_posicion.nombres', 'asc')
                            ->paginate($pageLimit);
        }
        else{
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
                        ->orderBy('tb_empleados_x_posicion.nombres', 'asc')
                        ->paginate($pageLimit);
        }

        if(count($dataUser) > 0)
        {
            $offset = ($dataUser->currentPage() - 1) * $pageLimit;
            $total_pages = ceil($dataUser->total() / $pageLimit);

            array_push($data, array("estatus" => 'success', 
                                    "offset" => $offset,
                                    "totalPage" => $total_pages,
                                    "dataUser" => $dataUser));
        }
        else
        {
            array_push($data, array("message" => "Error en la busqueda."));
        }

        return response()->json($data); 
    }

    public function dataUser(Request $request)
    { 
        $data = array();
        $dataUser = DB::table('users')
                        ->leftjoin('tb_empleados_x_posicion', 'users.cod_empleado', '=','tb_empleados_x_posicion.cod_empleado_empresa')
                        ->leftjoin('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=','tb_posiciones_x_departamento.cod_posicion')
                        ->where([
                                ['users.id', '=', $request->id_usuario]
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
                        ->get();
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
