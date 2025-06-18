<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;


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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/theater', [TheaterController::class, 'create']);
    Route::get('/theaters/{id}', [TheaterController::class, 'show']);
    Route::post('/theaters', [TheaterController::class, 'store']);
    Route::put('/theaters/{id}', [TheaterController::class, 'edit']);
    Route::delete('/theaters/{id}', [TheaterController::class, 'delete']);
});





Route::get('/movies', [MovieController::class, 'apiIndex']);
Route::get('/showtimes', [ShowtimeController::class, 'apiIndex']);
Route::get('/theaters', [TheaterController::class, 'apiIndex']);

