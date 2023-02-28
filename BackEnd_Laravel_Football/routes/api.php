<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EntrenadorController;
use App\Http\Controllers\EstadistiquesPartitController;
use App\Http\Controllers\PartitController;
use App\Http\Controllers\TransferenciesController;

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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

//per fer que el resource tingui les routes que nosaltres volem.
//Route::resource('photo', 'PhotoController', ['only' => ['index', 'show']]); 
//Route::resource('photo', 'PhotoController', ['except' => ['create', 'store', 'update', 'destroy']]); 


Route::middleware('auth:api')->group(function () {
    //usuari
    Route::put('actualitzarUsuari/{id}', [PassportAuthController::class, 'update']);
    Route::post('logout', [PassportAuthController::class, 'logout']);
    Route::get('renew', [PassportAuthController::class, 'renew']);

    //jugadors
    Route::get('allJugadors', [JugadorController::class, 'allJugadors']);
    Route::get('onlyJugador/{id}', [JugadorController::class, 'onlyJugador']);
    Route::get('jugadorsDiferentClub/{id}', [JugadorController::class, 'jugadorsDiferentClub']);
    Route::get('freeJugadors', [JugadorController::class, 'jugadorsLliures']);
    Route::get('clubJugadors/{club}', [JugadorController::class, 'clubJugadors']);
    Route::post('nouJugador', [JugadorController::class, 'nouJugador']);
    Route::delete('borrarJugador/{id}', [JugadorController::class, 'borrarJugador']);
    Route::patch('actualitzarJugador/{jugador}', [JugadorController::class, 'actualitzarJugador']);

    //clubs
    Route::get('allClubs', [ClubController::class, 'allClubs']);
    Route::get('onlyClub/{id}', [ClubController::class, 'onlyClub']);
    Route::patch('actualitzarClub/{club}', [ClubController::class, 'actualitzarClub']);

    //entrenadors
    Route::get('allEntrenadors', [EntrenadorController::class, 'allEntrenadors']);
    Route::get('onlyEntrenador', [EntrenadorController::class, 'onlyEntrenador']);
    Route::get('entrenadorsClub', [EntrenadorController::class, 'entrenadorsClub']);
    Route::get('entrenadorsDifferentClub', [EntrenadorController::class, 'entrenadorsDifferentClub']);
    Route::patch('actualitzarEntrenador/{entrenador}', [EntrenadorController::class, 'actualitzarEntrenador']);


    //transferencies jugador
    Route::get('allTransferenciesJugadorall', [TransferenciesController::class, 'allTransferenciesJugadorall']);
    Route::get('allTransferenciesJugador/{jugador}', [TransferenciesController::class, 'allTransferenciesJugador']);
    Route::get('allTransferenciesJugadorclub/{club}', [TransferenciesController::class, 'allTransferenciesJugadorclub']);
    Route::post('compraJugador', [TransferenciesController::class, 'compraJugador']);
    Route::post('ventaJugador', [TransferenciesController::class, 'ventaJugador']);

    //transferencies entrenador
    Route::get('allTransferenciesEntrenadorall', [TransferenciesController::class, 'allTransferenciesEntrenadorall']);
    Route::get('allTransferenciesEntrenador/{entrenador}', [TransferenciesController::class, 'allTransferenciesEntrenador']);
    Route::get('allTransferenciesEntrenadorclub/{club}', [TransferenciesController::class, 'allTransferenciesEntrenadorclub']);
    Route::post('compraEntrenador', [TransferenciesController::class, 'compraEntrenador']);
    Route::post('ventaEntrenador', [TransferenciesController::class, 'ventaEntrenador']);

    //partits
    Route::get('allgameClub/{club}', [PartitController::class, 'allgameClub']);
    Route::get('gameClub/{id}', [PartitController::class, 'gameClub']);
    Route::get('comprovacioEquipJugadors/{id}', [PartitController::class, 'comprovacioEquipJugadors']);
    Route::post('creategame', [PartitController::class, 'creategame']);

    //events partit
    Route::get('gameStats/{id}', [EstadistiquesPartitController::class, 'gameStats']);
    Route::post('creategameStats', [EstadistiquesPartitController::class, 'creategameStats']);

});
