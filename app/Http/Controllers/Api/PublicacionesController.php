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
use App\Models\MultimediaXPublicaciones;
use App\Models\PosicionesXDepartamentos;
use App\Models\ReaccionesXPublicaciones;
use App\Models\EmpresasXGruposEmpresariales;
use App\Http\Controllers\ValidacionesController;
use App\Http\Requests\FileUploadRequest;

class PublicacionesController extends Controller
{
    //Index

    public function index(Request $request)
    {
        $cod_publicacion = (isset($request->cod_publicacion) ? $request->cod_publicacion : '');
        $posts = array();

        if($cod_publicacion != '')
        {
            $data_public = DB::table('tb_publicaciones')
                                ->join('users' ,'tb_publicaciones.cod_usuario','=','users.id')
                                ->select('tb_publicaciones.*','users.name','users.cod_empleado')
                                ->where([['tb_publicaciones.estatus', 'A'], ['tb_publicaciones.cod_padre_publicacion', '=', $cod_publicacion]])
                                ->orderBy('tb_publicaciones.created_at','DESC')
                                ->get();
        }
        else
        {
            $data_public = DB::table('tb_publicaciones')
                                ->join('users' ,'tb_publicaciones.cod_usuario','=','users.id')
                                ->select('tb_publicaciones.*','users.name','users.cod_empleado')
                                ->where([['tb_publicaciones.estatus', 'A'], ['tb_publicaciones.cod_padre_publicacion', '=', NULL]])
                                ->orderBy('tb_publicaciones.created_at','DESC')
                                ->get();
        }

        //$url_http = 'https://9314-152-166-159-138.ngrok.io';
        $url_http =  'http://18.217.9.139/';
        
        if(count($data_public) > 0)
        {
            foreach($data_public as $post)
            {
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
                        $nombre_ima = $url_http.'/images/gruposEmpresariales/post/multimedia/'.$valore_img->nombre_archivo;
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
                $postComent = DB::table('tb_publicaciones')
                                ->where([['estatus', 'A'], ['cod_padre_publicacion', '=', $post->cod_publicacion]])
                                ->count();

                $rand = rand(1,9);
                $list_popst = array(
                                "estatus" => "success",
                                "cod_publicacion" => $post->cod_publicacion,
                                "nombre" =>  $post->name,
                                "avatar" =>  $url_http.'/images/avatars/'.$rand.'.png',
                                "tipo" =>  $nombre_posicion,
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
        else
        {
            array_push($posts, array("estatus" => "error"));
        }

        return response()->json($posts); 
    }

    public function publicar(Request $request)
    {
        $data = array();
       
        $cod_padre_publicacion  = (isset($request->cod_publicacion) ? $request->cod_publicacion : '');
        $cod_usuario            = (isset($request->cod_usuario) ? $request->cod_usuario : '');
        $cod_empleado           = (isset($request->cod_empleado) ? $request->cod_empleado : '');
        $tipo_publicacion       = (isset($request->tipo_publicacion) ? $request->tipo_publicacion : '');  
        $commentario            = (isset($request->commentario) ? $request->commentario : '');
        $grupos                 = (isset($request->grupos) ? $request->grupos : '');

        
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

            $cod_comunidad          = $grupos;
            $cod_tipo_publicacion   = $tipo_publicacion;
            $texto                  = $commentario;
            $permite_reaccion       = 'S';
            $permite_comentario     = 'S';

            $validate_input = new ValidacionesController;
            
            
            $validate_input->validateValue($data_public, 'cod_padre_publicacion', $cod_padre_publicacion);
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
                            $ruta           = public_path("images/gruposEmpresariales/post/multimedia/");

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

                array_push($data, array("estatus" => 'success', "message" => "Publicaciones realizada")); 
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error al realizar la publicacion"));
            }
        }

        return response()->json($data); 
    }

    public function getB64Image($base64_image)
    {  
        // Obtener el String base-64 de los datos         
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        // Decodificar ese string y devolver los datos de la imagen        
        $image = base64_decode($image_service_str);   
        // Retornamos el string decodificado
        return $image; 
    }

    public function getB64Extension($base64_image, $full=null)
    {  
        // Obtener mediante una expresión regular la extensión imagen y guardarla
        // en la variable "img_extension"        
        preg_match("/^data:image\/(.*);base64/i",$base64_image, $img_extension);   
        // Dependiendo si se pide la extensión completa o no retornar el arreglo con
        // los datos de la extensión en la posición 0 - 1
        return ($full) ?  $img_extension[0] : $img_extension[1];  
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
