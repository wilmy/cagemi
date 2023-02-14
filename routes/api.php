<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\CargaDatosController;
use App\Http\Controllers\Api\PublicacionesController;
use App\Http\Controllers\Api\UsuariosXEmpresaController;
use App\Http\Controllers\Api\TiposPublicacionesController;
use App\Http\Controllers\Api\EmpleadosXPosicionApiController;
use App\Http\Controllers\Api\DireccionesVicepresidenciasController;
use App\Http\Controllers\Api\PosicionesXDepartamentosApiController;
use App\Http\Controllers\Api\EmpresasXGruposEmpresarialesController;
use App\Http\Controllers\Api\DepartamentosXVicepresidenciasApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function(){

});

//Empresas activas 
Route::get('/empresas', [EmpresasXGruposEmpresarialesController::class, 'index']);


//Login de inicio y validacion de usuario 
Route::post('/login', [LoginController::class, 'index']);

//Usuarios por grupo empresarial
Route::post('/usuariosEmpresa', [UsuariosXEmpresaController::class, 'index']);


//Datos del usuario
Route::post('/dataUser', [UsuariosXEmpresaController::class, 'dataUser']);


//Para generar el token de acceso
Route::post('/generate_token', [LoginController::class, 'generate_token']);


//Completar los datos del usuario
Route::post('/completarDatos', [LoginController::class, 'completarDatos']);
Route::post('/cambioPassword', [LoginController::class, 'cambioPassword']);


//Tipos de Publicaciones
Route::get('/tipoPublicaciones', [TiposPublicacionesController::class, 'index']);

//Publicaciones
Route::get('/publicaciones', [PublicacionesController::class, 'index']);
Route::post('/publicaciones', [PublicacionesController::class, 'index']);

Route::post('/comentarios_publicaciones', [PublicacionesController::class, 'comentarios_publicaciones']);
Route::post('/publicar_comentario', [PublicacionesController::class, 'publicar_comentario']);


Route::post('/publicar', [PublicacionesController::class, 'publicar']);
Route::post('/likepublicacion', [PublicacionesController::class, 'likepublicacion']);





Route::get('/tempdata', [CargaDatosController::class, 'index']);
Route::get('/tempdata/{page}', [CargaDatosController::class, 'index']);

Route::get('/vicepresidencias/{cod_empresa}', [DireccionesVicepresidenciasController::class, 'index']);

Route::get('/departamentos/{cod_vicepresidencia}/{grupo}', [DepartamentosXVicepresidenciasApiController::class, 'index']);

Route::get('/posiciones/{cod_departamento}/{grupo}', [PosicionesXDepartamentosApiController::class, 'index']);

Route::get('/empleados/{pagina}/{grupo}', [EmpleadosXPosicionApiController::class, 'index']);