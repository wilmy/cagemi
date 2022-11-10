<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicacionesController extends Controller
{
    //Index

    public function index(Request $request)
    {
        $url_http = 'https://968f-201-229-162-15.ngrok.io';
        $posts = array(
                array(
                    "estatus" => "success",
                    "nombre" =>  'Wilmy Rodriguez',
                    "avatar" =>  $url_http.'/images/avatars/1.png',
                    "tipo" =>  'V.P de Finanzas',
                    "postImage" =>  $url_http.'/images/slider/02.jpg',
                    "postComentario" =>  '',
                    "postText" =>  '',
                    "postLike" =>  100,
                    "postComent" =>  32,
                    "comentario" =>  'Travel and you will born for a second time.',
                    "activeInput" =>  true,
                    "configPost" =>  false
                ),
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/2.png',
                    "tipo" =>  'samguy',
                    "postImage" =>  '',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  236,
                    "postComent" =>  98,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  true
                )
                ,
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/3.png',
                    "tipo" =>  'samguy',
                    "postImage" =>   $url_http.'/images/slider/03.jpg',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  569,
                    "postComent" =>  32,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  false
                )
                ,
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/4.png',
                    "tipo" =>  'samguy',
                    "postImage" =>   $url_http.'/images/slider/06.jpg',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  478,
                    "postComent" =>  12,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  false
                )
                ,
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/5.png',
                    "tipo" =>  'samguy',
                    "postImage" =>  '',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  75698,
                    "postComent" =>  236,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  true
                )
                ,
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/6.png',
                    "tipo" =>  'samguy',
                    "postImage" =>  '',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  785,
                    "postComent" =>  369,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  true
                )
                ,
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/7.png',
                    "tipo" =>  'samguy',
                    "postImage" =>  '',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  522,
                    "postComent" =>  36,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  true
                )
                ,
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/8.png',
                    "tipo" =>  'samguy',
                    "postImage" =>  '',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  85,
                    "postComent" =>  4,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  true
                )
                ,
                array(
                    "estatus" => "success",
                    "nombre" =>  'Sam Guy',
                    "avatar" =>  $url_http.'/images/avatars/9.png',
                    "tipo" =>  'samguy',
                    "postImage" =>   $url_http.'/images/slider/10.jpg',
                    "postComentario" =>  "On a first-time visit to New Orleans, there's so much to see and do.",
                    "postText" =>  '',
                    "postLike" =>  5,
                    "postComent" =>  9,
                    "comentario" =>  '',
                    "activeInput" =>  true,
                    "configPost" =>  false
                )
        );

        return response()->json($posts); 
    }
}
