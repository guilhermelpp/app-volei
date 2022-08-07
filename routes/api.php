<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to the API',
        'version' => '1.0.0',
    ]);
});

Route::apiResource('/players', PlayerController::class);

Route::apiResource('/player-lists', PlayerListController::class);
Route::post('/player-lists/{playerList}', [PlayerListController::class, 'storePlayer']);
Route::get('/player-lists/{playerList}/game', [GameController::class, 'createGame']);

Route::put('/games/{game}', [GameController::class, 'update']);

Route::patch('/games/{game}/score', [GameController::class, 'updateScore']);
Route::patch('/games/{game}/end-game', [GameController::class, 'endGame']);
Route::patch('/games/{game}/cancel-game', [GameController::class, 'removePlayerToGame']);
