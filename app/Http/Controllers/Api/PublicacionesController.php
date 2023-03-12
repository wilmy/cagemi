<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Publicaciones;
use App\Models\GrupoEmpresarial;
use App\Models\EmpleadosXPosicion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileUploadRequest;
use App\Models\MultimediaXPublicaciones;
use App\Models\PosicionesXDepartamentos;
use App\Models\ReaccionesXPublicaciones;
use App\Models\ComentariosXPublicaciones;
use App\Models\EmpresasXGruposEmpresariales;
use App\Http\Controllers\ValidacionesController;
use App\Http\Controllers\Notifications\NewNotifications;

class PublicacionesController extends Controller
{
    public function index(Request $request)
    {
        $cod_publicacion        = (isset($request->cod_publicacion) ? $request->cod_publicacion : '');
        $id_usuario             = (isset($request->id_usuario) ? $request->id_usuario : '');
        $cod_grupo_empresarial  = (isset($request->cod_grupo_empresarial) ? $request->cod_grupo_empresarial : '');
        
        $perfil = (isset($request->perfil) ? $request->perfil : false);
        $pageLimit = (isset($request->pageLimit) ? $request->pageLimit : 5);

        $posts = array();
        
        if($perfil){
            $data_public = DB::table('tb_publicaciones as p')
                                ->join('users as u','p.cod_usuario','=','u.id')
                                ->leftjoin('tb_empleados_x_posicion as e', 'e.cod_empleado_empresa', '=', 'u.cod_empleado')
                                ->select('p.*','u.id','u.cod_grupo_empresarial','u.name','u.cod_empleado','e.foto')
                                ->where([['p.cod_usuario', $id_usuario], ['u.cod_grupo_empresarial', '=', $cod_grupo_empresarial]])
                                ->orderBy('u.prioridad_publicacion', 'DESC')
                                ->orderBy('p.created_at','DESC')
                                ->paginate($pageLimit);
        }
        else{
            $data_public = DB::table('tb_publicaciones as p')
                                ->join('users as u', 'p.cod_usuario', '=', 'u.id')
                                ->leftjoin('tb_empleados_x_posicion as e', 'e.cod_empleado_empresa', '=', 'u.cod_empleado')
                                ->leftJoin('tb_visitas_x_publicaciones as v', function ($join) use ($id_usuario) {
                                    $join->on('p.cod_publicacion', '=', 'v.cod_publicacion')
                                        ->where('v.cod_usuario', '=', $id_usuario);
                                })
                                ->select('p.*','u.id','u.cod_grupo_empresarial','u.name','u.cod_empleado','e.foto')
                                ->whereNull('v.cod_publicacion')
                                ->where([['u.cod_grupo_empresarial', '=', $cod_grupo_empresarial]])
                                ->orderByDesc('u.prioridad_publicacion')
                                ->orderByDesc('p.created_at')
                                ->paginate($pageLimit);
        }
        
       
        //$url_http = 'https://0c24-38-44-16-250.ngrok.io';
        $url_http =  'http://18.217.5.208';
        
        if(count($data_public) > 0)
        {
            foreach($data_public as $post)
            {
                $ruta_img = $url_http.'/images/gruposEmpresariales/grupo'.$post->cod_grupo_empresarial;
                $reacciones_publ = ReaccionesXPublicaciones::where('cod_publicacion', $post->cod_publicacion)->count();
                //$grupo_empresarial = EmpresasXGruposEmpresariales::find($post->cod_comunidad);
                //$nombre_grupo = ($grupo_empresarial != '' ? $grupo_empresarial->nombre : '');
                $postComent = 0;

                $array_imagenes = array();

                $data_image_publ = MultimediaXPublicaciones::where('cod_publicacion', $post->cod_publicacion)->get();
                $nombre_ima = '';
                if(count($data_image_publ) > 0)
                {
                    foreach($data_image_publ as $valore_img)
                    {
                        $nombre_ima = $ruta_img.'/post/multimedia/'.$valore_img->nombre_archivo;
                        array_push($array_imagenes, $nombre_ima);
                    }
                }

                $nombre_posicion = '';
                $posisionEmpleado = EmpleadosXPosicion::where('cod_empleado_empresa', $post->cod_empleado)->get();
                if(count($posisionEmpleado) > 0)
                {
                    $posiciones = PosicionesXDepartamentos::where('cod_posicion', $posisionEmpleado[0]->cod_posicion)->get();
                    if(count($posiciones) > 0)
                    {
                        $nombre_posicion = $posiciones[0]->nombre_posicion;
                    }
                }

                //Total de comentario 
                $postComent = DB::table('tb_comentarios_x_publicaciones')
                                ->where([['cod_publicacion', '=', $post->cod_publicacion]])
                                ->count();

                $postLikeUser = 0;
                if($id_usuario != '')
                {
                    $postLikeUser = ReaccionesXPublicaciones::where([['cod_publicacion', $post->cod_publicacion],['cod_usuario', $id_usuario]])->count();
                }

                $offset = ($data_public->currentPage() - 1) * $pageLimit;
                $total_pages = ceil($data_public->total() / $pageLimit);

                $key = rand(1,9);
                $list_popst = array(
                                "estatus" => "success",
                                "offset" => $offset,
                                "totalPage" => $total_pages,
                                "id" => $post->id,
                                "key" => $key,
                                "cod_publicacion" => $post->cod_publicacion,
                                "nombre" =>  $post->name,
                                "avatar" =>  ($post->foto != '' ? $ruta_img.'/fotoEmpleados/'.$post->foto : null),
                                "tipo" =>  $nombre_posicion,
                                "postImage" =>  $array_imagenes,
                                "postComentario" =>  $post->texto,
                                "postText" =>  '',
                                "postLike" =>  $reacciones_publ,
                                "postLikeUser" => $postLikeUser,
                                "postComent" =>  $postComent,
                                "comentario" =>  '',
                                "permite_comentario" =>  $post->permite_comentario,
                                "permite_reaccion" =>  $post->permite_reaccion,
                                "activeInput" =>  false,
                                "configPost" =>  false
                            );

                array_push($posts, $list_popst);
            }
        }
        else
        {
            array_push($posts, array("estatus" => "error".$cod_grupo_empresarial));
        }

        return response()->json($posts); 
    }

    public function comentarios_publicaciones(Request $request)
    {
        $cod_publicacion = (isset($request->cod_publicacion) ? $request->cod_publicacion : '');
        $posts = array();

        $url_http =  'http://18.217.5.208';

        $data_public = DB::table('tb_comentarios_x_publicaciones as c')
                            ->join('users as u' ,'c.cod_usuario','=','u.id')
                            ->leftjoin('tb_empleados_x_posicion as e', 'e.cod_empleado_empresa', '=', 'u.cod_empleado')
                            ->select('c.*','u.name','u.cod_empleado', 'e.foto', 'u.cod_grupo_empresarial')
                            ->where([['c.cod_publicacion', '=', $cod_publicacion]])
                            ->orderBy('c.created_at','DESC')
                            ->get();
       
        if(count($data_public) > 0)
        {
            foreach($data_public as $post)
            {
                $nombre_posicion = '';
                $posisionEmpleado = EmpleadosXPosicion::where('cod_empleado_empresa', $post->cod_empleado)->get();
                if(count($posisionEmpleado) > 0)
                {
                    $posiciones = PosicionesXDepartamentos::where('cod_posicion', $posisionEmpleado[0]->cod_posicion)->get();
                    if(count($posiciones) > 0)
                    {
                        $nombre_posicion = $posiciones[0]->nombre_posicion;
                    }
                }

                $ruta_img = $url_http.'/images/gruposEmpresariales/grupo'.$post->cod_grupo_empresarial;
                $list_popst = array(
                                "estatus" => "success",
                                "cod_publicacion" => $post->cod_publicacion,
                                "nombre" =>  $post->name,
                                "avatar" =>  ($post->foto != '' ? $ruta_img.'/fotoEmpleados/'.$post->foto : null),
                                "tipo" =>  $nombre_posicion,
                                "postComentario" =>  $post->comentario
                            );

                array_push($posts, $list_popst);
            }            
        }
        else
        {
            array_push($posts, array("estatus" => "error". count($data_public)));
        }

        return response()->json($posts); 
    }

    

    public function publicar(Request $request)
    {
        $data = array();
       
        $cod_padre_publicacion  = (isset($request->cod_publicacion) ? $request->cod_publicacion : '');
        $cod_usuario            = (isset($request->cod_usuario) ? $request->cod_usuario : '');
        $cod_grupo_empresarial  = (isset($request->cod_grupo_empresarial) ? $request->cod_grupo_empresarial : '');
        
        $cod_empleado           = (isset($request->cod_empleado) ? $request->cod_empleado : '');
        $tipo_publicacion       = (isset($request->tipo_publicacion) ? $request->tipo_publicacion : '');  
        $commentario            = (isset($request->commentario) ? $request->commentario : '');
        $cod_comunidad          = (isset($request->cod_comunidad) ? $request->cod_comunidad : '');
        $permiteReaccion        = (isset($request->permiteReaccion) ? $request->permiteReaccion : 'N');
        $permitecomentario      = (isset($request->permitecomentario) ? $request->permitecomentario : 'N');

        
        if(empty($cod_usuario) && 
            empty($cod_empleado) && 
            empty($tipo_publicacion) && 
            empty($commentario) && 
            empty($grupos))
        {
            array_push($data, array("estatus" => 'error', "message" => "Todos los campos son obligatorios"));
        }
        else
        {
            $data_public = new Publicaciones; 

            //$cod_comunidad          = $grupos;
            $cod_tipo_publicacion   = $tipo_publicacion;
            $texto                  = $commentario;
            $permite_reaccion       = $permiteReaccion; 
            $permite_comentario     = $permitecomentario;

            $validate_input = new ValidacionesController;

            $validate_input->validateValue($data_public, 'cod_usuario', $cod_usuario);            
            $validate_input->validateValue($data_public, 'cod_comunidad', $cod_comunidad);
            $validate_input->validateValue($data_public, 'cod_tipo_publicacion', $cod_tipo_publicacion);
            $validate_input->validateValue($data_public, 'texto', $texto);
            $validate_input->validateValue($data_public, 'permite_reaccion', $permite_reaccion);
            $validate_input->validateValue($data_public, 'permite_comentario', $permite_comentario);
          
            if($data_public->save())
            {
                if($request->hasFile("imagenes"))
                {
                    $array_imagenes = $request->file("imagenes");
                    if(count($array_imagenes) > 0)
                    {
                        for($x = 0; $x < count($array_imagenes); $x++)
                        {
                            $imagen         = $array_imagenes[$x];
                            $nombreimagen   = uniqid().".".$imagen->guessExtension();
                            $ruta           = public_path("images/gruposEmpresariales/grupo".$cod_grupo_empresarial."/post/multimedia/");

                            if (!is_dir($ruta)) {
                                mkdir($ruta, 0775, true);
                            }

                            if(copy($imagen->getRealPath(),$ruta.$nombreimagen))
                            {
                                $up_images = new MultimediaXPublicaciones;

                                $up_images->cod_publicacion = $data_public->cod_publicacion;
                                $up_images->server = $ruta;
                                $up_images->nombre_archivo = $nombreimagen;
                                $up_images->save();
                            }
                        }
                    }
                }

                $dataUser = DB::table('users')->find($cod_usuario); 
                $nombre_usu = $dataUser->name;
                $nombre_usu = $dataUser->prioridad_publicacion;

                if($prioridad_publicacion >= 3)
                {
                    /**
                     * Lista de los usuarios con token para envio
                     */ 
                    $dataUserTokens = DB::table('users')
                                        ->where([['token_app','<>',''], ['cod_grupo_empresarial','=', 1]])
                                        ->pluck('token_app')->toArray();
                    /**
                     * Para las notificaciones de push notification
                     */
                    $notifications = new NewNotifications;
                    $datas = [
                        'channelName' => 'grupos',
                        'tokens' =>  $dataUserTokens,
                        'bodyMessage' => 'Hey!! '.$nombre_usu.' acaba de hacer una publicación, mira que dice!',
                    ];
        
                    $notifications->notificacion($datas);
                }

                array_push($data, array("estatus" => 'success', "message" => "Publicaciones realizada")); 
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error al realizar la publicacion"));
            }
        }

        return response()->json($data); 
    }


    public function publicar_comentario(Request $request)
    {
        $data = array();
       
        $cod_publicacion  = (isset($request->cod_publicacion) ? $request->cod_publicacion : '');
        $cod_usuario            = (isset($request->cod_usuario) ? $request->cod_usuario : '');
        $cod_empleado           = (isset($request->cod_empleado) ? $request->cod_empleado : ''); 
        $comentario            = (isset($request->commentario) ? $request->commentario : '');
        
        if(empty($cod_usuario) && 
            empty($comentario))
        {
            array_push($data, array("estatus" => 'error', "message" => "Todos los campos son obligatorios"));
        }
        else
        {
            $data_public = new ComentariosXPublicaciones; 

            $validate_input = new ValidacionesController;
            
            $validate_input->validateValue($data_public, 'cod_usuario', $cod_usuario);
            $validate_input->validateValue($data_public, 'cod_publicacion', $cod_publicacion);
            $validate_input->validateValue($data_public, 'comentario', $comentario);
          
            if($data_public->save())
            {
                array_push($data, array("estatus" => 'success', "message" => "Comentario realizado")); 
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error al realizar el comentario"));
            }
        }

        return response()->json($data); 
    }

    public function likepublicacion(Request $request)
    {
        $data = array();
       
        $id_usuario        = $request->cod_empleado;
        $likepublicacion   = (isset($request->likepublicacion) ? $request->likepublicacion : 0);  
        $cod_publicacion   = (isset($request->cod_publicacion) ? $request->cod_publicacion : ''); 

        if(empty($id_usuario) && empty($likepublicacion) && empty($cod_publicacion))
        {
            array_push($data, array("estatus" => 'error', "message" => "Todos los campos son obligatorios"));
        }

        $postLikeUser = ReaccionesXPublicaciones::where([
                                                            ['cod_publicacion', $cod_publicacion],
                                                            ['cod_usuario', $id_usuario]
                                                        ])->count();
        

        if($postLikeUser <= 0)
        {
            $data_public = new ReaccionesXPublicaciones; 

            $validate_input = new ValidacionesController;
            $validate_input->validateValue($data_public, 'cod_publicacion', $cod_publicacion);
            $validate_input->validateValue($data_public, 'cod_usuario', $id_usuario);

            if($data_public->save())
            {
                $postLikeUser = ReaccionesXPublicaciones::where([['cod_publicacion', $cod_publicacion],['cod_usuario', $id_usuario]])->count();
                $totalLikes = ReaccionesXPublicaciones::where('cod_publicacion', $cod_publicacion)->count();
                array_push($data, array("estatus" => 'success', "totalLikes" => $totalLikes, "message" => "Me gusta", "postLikeUser" => $postLikeUser)); 
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error"));
            }
        }
        else
        {
            ReaccionesXPublicaciones::where([['cod_publicacion', $cod_publicacion], ['cod_usuario', $id_usuario]])->delete();
            $totalLikes = ReaccionesXPublicaciones::where('cod_publicacion', $cod_publicacion)->count();
            array_push($data, array("estatus" => 'success', "totalLikes" => $totalLikes, "postLikeUser" => 0, "message" => "Me gusta")); 
        }

        return response()->json($data); 
    }

    
}
