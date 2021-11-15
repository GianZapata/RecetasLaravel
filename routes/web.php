<?php
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, PATCH, DELETE');
header('Access-Control-Allow-Headers: Accept, Content-Type, X-Auth-Token, Origin, Authorization');

use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\WelcomeController;
use App\Models\Receta;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/nosotros','RecetaController@hola');

Route::get('/',[InicioController::class,'index'])->name('inicio.index');

// Route::get('/recetas',RecetaController::class);

Route::get('/recetas',[RecetaController::class,'index'])->name('recetas.index');
Route::get('/recetas/create',[RecetaController::class,'create'])->name('recetas.create');
Route::post('/recetas', [RecetaController::class,'store'])->name('recetas.store');
Route::get('/recetas/{receta}', [RecetaController::class,'show'])->name('recetas.show');
Route::get('/recetas/{receta}/edit', [RecetaController::class,'edit'])->name('recetas.edit');
Route::put('/recetas/{receta}',[RecetaController::class,'update'])->name('recetas.update');
Route::delete('/recetas/{receta}',[RecetaController::class,'destroy'])->name('recetas.destroy');

Route::get('/categoria/{categoriaReceta}',[CategoriasController::class,'show'])->name('categorias.show');

//Buscador de recetas
Route::get('/buscar',[RecetaController::class,'search'])->name('buscar.show');

// Route::resource('recetas', RecetaController::class);


Route::get('/perfiles/{perfil}',[PerfilController::class,'show'])->name('perfiles.show');
Route::get('/perfiles/{perfil}/edit',[PerfilController::class,'edit'])->name('perfiles.edit');
Route::put('/perfiles/{perfil}',[PerfilController::class,'update'])->name('perfiles.update');

//Almacena los likes de las recetas
Route::post('/recetas/{receta}', [LikesController::class,'update'])->name('likes.update');
Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
