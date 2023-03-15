<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\CargaDatos;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Felicitaciones;
use App\Models\EmpleadosXPosicion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ValidacionesController;

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
                                    'users.token_app', 
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
                                'users.token_app', 
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

    public function cumpleanos_x_usuarios(Request $request)
    { 
        $pageLimit = (isset($request->pageLimit) ? $request->pageLimit : 15);
        $text = (isset($request->text) ? $request->text : '');
        $busq = (isset($request->busq) ? $request->busq : false);
        $id_usuario = (isset($request->id_usuario) ? $request->id_usuario : false);
        $cod_grupo_empresarial = (isset($request->cod_grupo_empresarial) ? $request->cod_grupo_empresarial : false);

        $fecha_dia = date('Y-m-d');
        $year = date('Y');
        $mes = date('m');
        $dia = date('d');
        /*
            ->whereMonth('fecha_limite', date('m'))
            ->whereDay('fecha_limite', date('d'))
        */
        $data = array();
        if($busq)
        {
            $dataUser = DB::table('users as u')
                            ->leftjoin('tb_empleados_x_posicion as e', 'u.cod_empleado', '=','e.cod_empleado_empresa')
                            ->leftjoin('tb_posiciones_x_departamento as p', 'e.cod_posicion', '=','p.cod_posicion')
                            ->leftjoin('tb_felicitaciones as f', function ($join) use ($id_usuario, $year) {
                                $join->on('f.id_usuario_felicitaciones', '=','u.id')
                                    ->where('f.id_usuario', '=', $id_usuario) 
                                    ->whereYear('f.fecha_felicitaciones', $year);
                            })
                            ->whereNull('f.id_usuario_felicitaciones')
                            ->where([ 
                                    ['u.cod_grupo_empresarial', '=', $cod_grupo_empresarial],
                                    ['e.nombres', 'LIKE', '%'.$text.'%']
                                ])
                            ->whereMonth('e.fecha_nacimiento', $mes)
                            ->whereDay('e.fecha_nacimiento', $dia)
                            ->select('e.*', 
                                    'p.nombre_posicion', 
                                    'u.token_autentication', 
                                    'u.token_app', 
                                    'u.password', 
                                    'u.cambio_password', 
                                    'u.id', 
                                    'u.cod_grupo_empresarial', 
                                    'u.profile_photo_path', 
                                    'u.email_verified_at')
                            ->orderBy('e.nombres', 'asc')
                            ->paginate($pageLimit);
        }
        else{
            $dataUser = DB::table('users as u')
                        ->leftjoin('tb_empleados_x_posicion as e', 'u.cod_empleado', '=','e.cod_empleado_empresa')
                        ->leftjoin('tb_posiciones_x_departamento as p', 'e.cod_posicion', '=','p.cod_posicion') 
                        ->leftjoin('tb_felicitaciones as f', function ($join) use ($id_usuario, $year) {
                            $join->on('f.id_usuario_felicitaciones', '=','u.id')
                                ->where('f.id_usuario', '=', $id_usuario) 
                                ->whereYear('f.fecha_felicitaciones', $year);
                        })
                        ->whereNull('f.id_usuario_felicitaciones')
                        ->where([
                                ['u.cod_grupo_empresarial', '=', $cod_grupo_empresarial]
                            ])
                        ->whereMonth('e.fecha_nacimiento', $mes)
                        ->whereDay('e.fecha_nacimiento', $dia)
                        ->select('e.*', 
                                'p.nombre_posicion', 
                                'u.token_autentication', 
                                'u.token_app', 
                                'u.password', 
                                'u.cambio_password', 
                                'u.id', 
                                'u.cod_grupo_empresarial', 
                                'u.profile_photo_path', 
                                'u.email_verified_at')
                        ->orderBy('e.nombres', 'asc')
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
                        ->leftjoin('tb_grupos_empresariales as g', 'g.cod_grupo_empresarial', '=','users.cod_grupo_empresarial')
                        ->where([
                                ['users.id', '=', $request->id_usuario]
                            ])
                        ->select('tb_empleados_x_posicion.*', 
                                 'tb_posiciones_x_departamento.nombre_posicion', 
                                 'users.token_autentication', 
                                 'users.token_app', 
                                 'users.password', 
                                 'users.cambio_password', 
                                 'users.id', 
                                 'users.cod_grupo_empresarial', 
                                 'users.profile_photo_path', 
                                 'users.email_verified_at', 
                                 'g.nombre as nombreGrupo')
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

    public function felicitaciones_empleados(Request $request)
    {
        $data = array();
        $fecha_dia = date('Y-m-d');
       
        $id_usuario                     = $request->id_usuario;
        $id_usuario_felicitaciones      = $request->id_usuario_felicitaciones;  
         
        if(empty($id_usuario) && empty($id_usuario_felicitaciones))
        {
            array_push($data, array("estatus" => 'error', "message" => "Todos los campos son obligatorios"));
        }

         
        $data_f = new Felicitaciones;  
        $validate_input = new ValidacionesController;
        $validate_input->validateValue($data_f, 'id_usuario', $id_usuario);
        $validate_input->validateValue($data_f, 'id_usuario_felicitaciones', $id_usuario_felicitaciones);
        $validate_input->validateValue($data_f, 'fecha_felicitaciones', $fecha_dia); 

        if($data_f->save())
        { 
            array_push($data, array("estatus" => 'success', "message" => "OK")); 
        }
        else
        {
            array_push($data, array("estatus" => 'error', "message" => "Error"));
        }
         

        return response()->json($data); 
    }
}
