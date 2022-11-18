<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Publicaciones;
use App\Models\GrupoEmpresarial;
use App\Http\Controllers\Controller;
use App\Models\MultimediaXPublicaciones;
use App\Models\ReaccionesXPublicaciones;
use App\Models\EmpresasXGruposEmpresariales;
use App\Http\Controllers\ValidacionesController;

class PublicacionesController extends Controller
{
    //Index

    public function index(Request $request)
    {
        $posts = array();
        $data_public = Publicaciones::where('estatus', 'A')
                                    ->orderBy('created_at','DESC')
                                    ->get();

        //$url_http = 'https://2511-190-80-245-171.ngrok.io';
        $url_http =  'http://18.217.9.139/';
        
        if(count($data_public) > 0)
        {
            foreach($data_public as $post)
            {
                $reacciones_publ = ReaccionesXPublicaciones::where('cod_publicacion', $post->cod_publicacion)->count();
                $grupo_empresarial = EmpresasXGruposEmpresariales::find($post->cod_comunidad);
                $nombre_grupo = ($grupo_empresarial != '' ? $grupo_empresarial->nombre : '');
                $postComent = 0;

                $array_imagenes = array();

                $data_image_publ = MultimediaXPublicaciones::where('cod_publicacion', $post->cod_publicacion)->get();
                $nombre_ima = '';
                if(count($data_image_publ) > 0)
                {
                    foreach($data_image_publ as $valore_img)
                    {
                        $nombre_ima = $url_http.'/images/gruposEmpresariales/post/multimedia/'.$valore_img->nombre_archivo;
                        array_push($array_imagenes, $nombre_ima);
                    }
                }

                $list_popst = array(
                                "estatus" => "success",
                                "cod_publicacion" => $post->cod_publicacion,
                                "nombre" =>  'Wilmy Rodriguez',
                                "avatar" =>  $url_http.'/images/avatars/1.png',
                                "tipo" =>  $nombre_grupo,
                                "postImage" =>  $array_imagenes,
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
       
        $cod_empleado       = (isset($request->cod_empleado) ? $request->cod_empleado : '');
        $tipo_publicacion   = (isset($request->tipo_publicacion) ? $request->tipo_publicacion : '');  
        $commentario        = (isset($request->commentario) ? $request->commentario : '');
        $grupos             = (isset($request->grupos) ? $request->grupos : '');

        if(empty($cod_empleado) && 
            empty($tipo_publicacion) && 
            empty($commentario) && 
            empty($grupos))
        {
            array_push($data, array("estatus" => 'error', "message" => "Todos los campos son obligatorios".$cod_empleado));
        }
        else
        {
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
                $imagenes = '';
                if($request->hasFile("imagenes"))
                {
                    $imagen = $request->file("imagenes");
                    $nombreimagen = uniqid().".".$imagen->guessExtension();
                    $ruta = public_path("images/gruposEmpresariales/post/multimedia/");

                    copy($imagen->getRealPath(),$ruta.$nombreimagen);
                    $imagenes = $nombreimagen;

                    $up_images = new MultimediaXPublicaciones;

                    $up_images->cod_publicacion = $data_public->cod_publicacion;
                    $up_images->server = $ruta;
                    $up_images->nombre_archivo = $nombreimagen;
                    $up_images->save();
                }

                array_push($data, array("estatus" => 'success', "message" => "Publicaciones realizada".$imagenes)); 
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error al realizar la publicacion"));
            }
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
            $totalLikes = ReaccionesXPublicaciones::where('cod_publicacion', $cod_publicacion)->count();
            array_push($data, array("estatus" => 'success', "totalLikes" => $totalLikes, "message" => "Me gusta")); 
        }
        else
        {
            array_push($data, array("estatus" => 'error', "message" => "Error"));
        }

        return response()->json($data); 
    }

    
}
