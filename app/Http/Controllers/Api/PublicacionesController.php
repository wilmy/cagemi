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
        $posts = array();
        $data_public = DB::table('tb_publicaciones')
                            ->join('users' ,'tb_publicaciones.cod_usuario','=','users.id')
                            ->select('tb_publicaciones.*','users.name','users.cod_empleado')
                            ->where('tb_publicaciones.estatus', 'A')
                            ->orderBy('tb_publicaciones.created_at','DESC')
                            ->get();

        $url_http = 'https://2511-190-80-245-171.ngrok.io';
        //$url_http =  'http://18.217.9.139/';
        
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

                $list_popst = array(
                                "estatus" => "success",
                                "cod_publicacion" => $post->cod_publicacion,
                                "nombre" =>  $post->name,
                                "avatar" =>  $url_http.'/images/avatars/2.png',
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

        return response()->json($posts); 
    }

    public function publicar(Request $request) 
    {
        try {
          
          $fileName = time().'_'.$request->file->getClientOriginalName();
          $request->file('file')->storeAs('uploads', $fileName, 'public');

          //$file_extension = $request->file->extension();
            //$file_mime_type =  $request->file->extension();

           // move_uploaded_file($file_mime_type['name'], '/public/dc8da8ec-eefe-4f2f-a7da-84e361c70a1f.jpeg');

          //$file_mime_type->storeAs('uploads', $file_mime_type['_data']['name'], 'public');
            //var_dump($file_mime_type );
          $pat = $request->file->getRealPath();
    
          return response()->json(
              [
                "estatus" => 'success',
                "message" => "File has been uploaded. ====".$pat,
              ],
            200);
    
        } catch (\Throwable $th) {
          return response()->json(
              [
                "estatus" => false, 
                "message" => "File upload failed.",
                //'errors' => ['file' = > "Oops! something went wrong."]
              ],
            400);
        }
    }

    public function publicar1(Request $request)
    {
        $data = array();
        $imagen = $request->file("file");
        $nombreimagen = uniqid().".".$imagen->guessExtension();

        $imagenes = $nombreimagen;
        array_push($data, array("estatus" => 'success', "message" => "Publicaciones realizada".$imagenes)); 
       
        /*$cod_usuario        = (isset($request->cod_usuario) ? $request->cod_usuario : '');
        $cod_empleado       = (isset($request->cod_empleado) ? $request->cod_empleado : '');
        $tipo_publicacion   = (isset($request->tipo_publicacion) ? $request->tipo_publicacion : '');  
        $commentario        = (isset($request->commentario) ? $request->commentario : '');
        $grupos             = (isset($request->grupos) ? $request->grupos : '');

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
            
            $validate_input->validateValue($data_public, 'cod_usuario', $cod_usuario);
            $validate_input->validateValue($data_public, 'cod_comunidad', $cod_comunidad);
            $validate_input->validateValue($data_public, 'cod_tipo_publicacion', $cod_tipo_publicacion);
            $validate_input->validateValue($data_public, 'texto', $texto);
            $validate_input->validateValue($data_public, 'permite_reaccion', $permite_reaccion);
            $validate_input->validateValue($data_public, 'permite_comentario', $permite_comentario);
          
            if($data_public->save())
            {
                $imagenes = ' >>';
                $base64_image =  $request->imagenes ;
                $img  = $this->getB64Image($base64_image);
                $img_extension = $this->getB64Extension($base64_image);


                $img_name = 'user_avatar'. time() . '.jpg';  

                Storage::disk('public')->put($img_name, $img);
                /*$imagen = $request->file("imagenes");
                $nombreimagen = uniqid().".".$imagen->guessExtension();
                $ruta = public_path("images/gruposEmpresariales/post/multimedia/");

                $imagenes = 'entro image'.$nombreimagen;
                copy($imagen->getRealPath(),$ruta.$nombreimagen);
                $imagenes = $nombreimagen;*/
 
               /*$file = $request->file('imagenes');
               $fileName = time().'_'.$file->getClientOriginalName();*/

               //$uri =  '';
               /*$request->imagenes->path();
               //$file->storeAs('uploads', $fileName, 'public');

                //$fileName = $file;
               $ruta = public_path("images/gruposEmpresariales/post/multimedia/");
               $file->move($ruta, $filename);
               //copy($file->getRealPath(), $ruta.$nombreimagen);

                //copy($file->getRealPath(), $ruta.$nombreimagen);

               //$imagenes = $file;

               //$img  = $this->getB64Image($base64_image);
               //$img = getB64Image($image_avatar_b64);
                // Obtener la extensión de la Imagen
               //$img_extension = $this->getB64Extension($base64_image);
                // Crear un nombre aleatorio para la imagen
                /*$img_name = 'user_avatar'. time() . '.jpg';   

                $ext = explode(';base64',$image);
                $ext = explode('/',$ext[0]);			
                $ext = $ext[1];		*/

               // $imagenes = $uri ;

                // Usando el Storage guardar en el disco creado anteriormente y pasandole a 
                // la función "put" el nombre de la imagen y los datos de la imagen como 
                // segundo parametro
                //Storage::disk('public')->put($img_name, $img);

                //$validator = Validator::make($request->all(), ['image' => ['required', File::image()->max(2 * 1024)]]);
                //if ($validator->fails()) return response()->json($validator->messages());
                //$image = new Image();
               /* $file = $request->file('imagenes');
                $fileName = time().'_'.$file->getClientOriginalName();
                //$file->storeAs('uploads', $fileName, 'public');
                $nombreimagen = uniqid() . "_" . $file->getClientOriginalName();

                $tmpim = $file;
                $ruta = public_path("images/gruposEmpresariales/post/multimedia/");
                //$file->move($ruta, $filename);
                //copy($file->getRealPath(), $ruta.$nombreimagen);

                //$url = URL::to('/') . '/public/images/' . $filename;
                //$image['url'] = $url;
                //$image->save();
                $imagenes = $file->getRealPath();
               // return response()->json(['isSuccess' => true, 'url' => $url]);

                /*if($request->hasFile("imagenes"))
                {
                    $imagen = $request->file("imagenes");
                    $nombreimagen = uniqid().".".$imagen->guessExtension();
                    $ruta = public_path("images/gruposEmpresariales/post/multimedia/");

                    $imagenes = 'entro image'.$nombreimagen;
                    /*copy($imagen->getRealPath(),$ruta.$nombreimagen);
                    $imagenes = $nombreimagen;

                    $up_images = new MultimediaXPublicaciones;

                    $up_images->cod_publicacion = $data_public->cod_publicacion;
                    $up_images->server = $ruta;
                    $up_images->nombre_archivo = $nombreimagen;
                    $up_images->save();*
                }

                array_push($data, array("estatus" => 'success', "message" => "Publicaciones realizada".$imagenes)); 
            }
            else
            {
                array_push($data, array("estatus" => 'error', "message" => "Error al realizar la publicacion"));
            }
        }*/

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
