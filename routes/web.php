<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\WebSocketController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\CargaDatosController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\PageLayoutController;
use App\Http\Controllers\ParametrosController;
use App\Http\Controllers\MiscellaneousController;
use App\Http\Controllers\UserInterfaceController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ValidacionDatosController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\GrupoEmpresarialController;
use App\Http\Controllers\EmpleadosXPosicionController;
use App\Http\Controllers\PosicionesXDepartamentosController;
use App\Http\Controllers\DireccionesVicepresidenciasController;
use App\Http\Controllers\EmpresasXGruposEmpresarialesController;
use App\Http\Controllers\DepartamentosXVicepresidenciasController;
use App\Http\Controllers\PushNotificationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::get('/websocket', 'WebSocketController@handle');
Route::get('websocket', [WebSocketController::class, 'handle']);

// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
/*
Route::get('/admin', function(){
    return view('admin.index');
})->middleware(['auth', 'role:admin'])->name('admin.index');*/

Route::middleware(['auth'])->name('admin.')->prefix('admin')->group(function(){
    Route::resource('/permissions', RoleController::class);

    Route::group(['prefix' => 'app'], function () {
        
        Route::put('roles/update', [RoleController::class, 'update'])->name('roles.update');
        Route::resource('/roles', RoleController::class);

        //Creacion de los usuario
        Route::resource('/users', UserController::class);

        //Permisos
        Route::get('permission', [PermissionController::class, 'access_permission'])->name('app-access-permission');

        //GrupoEmpresarial 
        Route::resource('/grupoEmpresarial', GrupoEmpresarialController::class);

        //Departamentos 
        Route::resource('/departamentos', DepartamentosXVicepresidenciasController::class);

        
        //Empresas por grupos empresariales 
        Route::resource('/empresas', EmpresasXGruposEmpresarialesController::class);

        //Direcciones y vicepresidencias
        Route::resource('/vicepresidencias', DireccionesVicepresidenciasController::class);


        //Departamentos
        Route::resource('/departamentosxvicepresidencias', DepartamentosXVicepresidenciasController::class);

        //Posiciones por Departamentos
        Route::resource('/posicionesxdepartamentos', PosicionesXDepartamentosController::class);

        
        //Empleados  por posiciones
        Route::resource('/empleadosxposiciones', EmpleadosXPosicionController::class);

        //Route::post('/empleadosxposiciones/downloadExcel', [EmpleadosXPosicionController::class, 'downloadExcel'])->name('downloadExcel');
        //Route::get('/empleadosxposiciones/{FiltroEmpresa}/{FiltroVicepresidencia}/{FiltroDepartamento}/{FiltroPosicion}', 
        //[EmpleadosXPosicionController::class, 'downloadExcel']);
        
        

        //Carga de datos temp
        Route::resource('/cargaDatos', CargaDatosController::class);


        //Comunidad del usuario
        Route::resource('/comunidad', ParametrosController::class);


        //Validacion de datos temporales
        Route::resource('/validacionDatos', ValidacionDatosController::class);

        Route::resource('/push', PushNotificationController::class);

    });
});


Route::group(['middleware' => 'auth:sanctum', 'verified'],function(){
    //Route::get('/', [StaterkitController::class, 'home'])->name('home');

    // Main Page Route
    //

    Route::get('/', [DashboardController::class, 'dashboardEcommerce'])->name('dashboard-ecommerce');
    Route::get('/error', [MiscellaneousController::class, 'error'])->name('error');
});

Route::get('/empleadosxposiciones/export', [EmpleadosXPosicionController::class, 'export'])->name('empleadosxposiciones.export');

//Route::post('/empleadosxposiciones/downloadExcel', [EmpleadosXPosicionController::class, 'downloadExcel']);

//Route::get('/empleadosxposiciones/{FiltroEmpresa}/{FiltroVicepresidencia}/{FiltroDepartamento}/{FiltroPosicion}', [EmpleadosXPosicionController::class, 'downloadExcel']);
        