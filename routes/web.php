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
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\PageLayoutController;
use App\Http\Controllers\MiscellaneousController;
use App\Http\Controllers\UserInterfaceController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\GrupoEmpresarialController;


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

Route::get('/admin', function(){
    return view('admin.index');
})->middleware(['auth', 'role:admin'])->name('admin.index');

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
    });
});

Route::group(['middleware' => 'auth:sanctum', 'verified'],function(){
    Route::get('/', [StaterkitController::class, 'home'])->name('home');

    // Main Page Route
    Route::get('/', [DashboardController::class, 'dashboardEcommerce'])->name('dashboard-ecommerce');
    Route::get('/error', [MiscellaneousController::class, 'error'])->name('error');
});