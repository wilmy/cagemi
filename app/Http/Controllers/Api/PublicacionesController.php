<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Publicaciones;
use App\Models\GrupoEmpresarial;
use App\Http\Controllers\Controller;
use App\Models\ReaccionesXPublicaciones;
use App\Http\Controllers\ValidacionesController;

class PublicacionesController extends Controller
{
    //Index

    public function index(Request $request)
    {
        $posts = array();
        $data_public = Publicaciones::where('estatus', 'A')->get();

        $url_http = 'https://968f-201-229-162-15.ngrok.io';
        
        if(count($data_public) > 0)
        {
            foreach($data_public as $post)
            {
                $reacciones_publ = ReaccionesXPublicaciones::where('cod_publicacion', $post->cod_publicacion)->count();
                $grupo_empresarial = GrupoEmpresarial::find($post->cod_comunidad);
                $nombre_grupo = ($grupo_empresarial != '' ? $grupo_empresarial->nombre : '');
                $postComent = 0;

                $list_popst = array(
                                "estatus" => "success",
                                "cod_publicacion" => $post->cod_publicacion,
                                "nombre" =>  'Wilmy Rodriguez',
                                "avatar" =>  $url_http.'/images/avatars/1.png',
                                "tipo" =>  $nombre_grupo,
                                "postImage" =>  '',
                                "postComentario" =>  $post->texto,
                                "postText" =>  '',
                                "postLike" =>  $reacciones_publ,
                                "postComent" =>  $postComent,
                                "comentario" =>  '',
                                "activeInput" =>  false,
                                "configPost" =>  false
                            );

                array_push($posts, $list_popst);
            }
        }

        return response()->json($posts); 
    }

    public function publicar(Request $request)
    {
        $data = array();
       
        $cod_empleado       = $request->cod_empleado;
        $tipo_publicacion   = (isset($request->tipo_publicacion) ? $request->tipo_publicacion : '');  
        $commentario        = (isset($request->commentario) ? $request->commentario : '');
        $grupos             = (isset($request->grupos) ? $request->grupos : '');

        if(empty($cod_empleado) && 
            empty($tipo_publicacion) && 
            empty($commentario) && 
            empty($grupos))
        {
            array_push($data, array("estatus" => 'error', "message" => "Todos los campos son obligatorios"));
        }

        $data_public = new Publicaciones; 

        $cod_comunidad          = $grupos;
        $cod_tipo_publicacion   = $tipo_publicacion;
        $texto                  = $commentario;
        $permite_reaccion       = 'S';
        $permite_comentario     = 'S';

        $validate_input = new ValidacionesController;
        $validate_input->validateValue($data_public, 'cod_comunidad', $cod_comunidad);
        $validate_input->validateValue($data_public, 'cod_tipo_publicacion', $cod_tipo_publicacion);
        $validate_input->validateValue($data_public, 'texto', $texto);
        $validate_input->validateValue($data_public, 'permite_reaccion', $permite_reaccion);
        $validate_input->validateValue($data_public, 'permite_comentario', $permite_comentario);

        if($data_public->save())
        {
            array_push($data, array("estatus" => 'success', "message" => "Publicaciones realizada")); 
        }
        else
        {
            array_push($data, array("estatus" => 'error', "message" => "Error al realizar la publicacion"));
        }

        return response()->json($data); 
    }

    public function likepublicacion(Request $request)
    {
        $data = array();
       
        $cod_empleado      = $request->cod_empleado;
        $likepublicacion   = (isset($request->likepublicacion) ? $request->likepublicacion : 0);  
        $cod_publicacion   = (isset($request->cod_publicacion) ? $request->cod_publicacion : ''); 

        if(empty($cod_empleado) && 
            empty($likepublicacion) && 
            empty($cod_publicacion))
        {
            array_push($data, array("estatus" => 'error', "message" => "Todos los campos son obligatorios"));
        }

        $data_public = new ReaccionesXPublicaciones; 

        $validate_input = new ValidacionesController;
        $validate_input->validateValue($data_public, 'cod_publicacion', $cod_publicacion);
        $validate_input->validateValue($data_public, 'cod_usuario', $cod_empleado);

        if($data_public->save())
        {
            array_push($data, array("estatus" => 'success', "message" => "Me gusta")); 
        }
        else
        {
            array_push($data, array("estatus" => 'error', "message" => "Error"));
        }

        return response()->json($data); 
    }

    
}
