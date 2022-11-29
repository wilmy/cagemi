<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\CargaDatosController;
use App\Http\Controllers\Api\PublicacionesController;
use App\Http\Controllers\Api\TiposPublicacionesController;
use App\Http\Controllers\Api\EmpresasXGruposEmpresarialesController;

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


//Completar los datos del usuario
Route::post('/completarDatos', [LoginController::class, 'completarDatos']);
Route::post('/cambioPassword', [LoginController::class, 'cambioPassword']);


//Tipos de Publicaciones
Route::get('/tipoPublicaciones', [TiposPublicacionesController::class, 'index']);

//Publicaciones
Route::get('/publicaciones', [PublicacionesController::class, 'index']);
Route::post('/publicaciones', [PublicacionesController::class, 'index']);
Route::post('/publicar', [PublicacionesController::class, 'publicar']);
Route::post('/likepublicacion', [PublicacionesController::class, 'likepublicacion']);





Route::get('/tempdata', [CargaDatosController::class, 'index']);
Route::get('/tempdata/{page}', [CargaDatosController::class, 'index']);

