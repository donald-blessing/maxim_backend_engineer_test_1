<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\MovieCharactersController;
use App\Http\Controllers\Api\MoviesController;
use App\Http\Controllers\Api\UsersController;
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
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');


//authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::apiResource('users', UsersController::class)->only('index', 'show', 'destroy');

    Route::prefix('movies')->group(function () {
        Route::get('/', [MoviesController::class, 'index']);
        Route::post('/', [MoviesController::class, 'store']);
        Route::get('/{movie}', [MoviesController::class, 'show']);
        Route::put('/{movie}', [MoviesController::class, 'update']);
        Route::delete('/{movie}', [MoviesController::class, 'destroy']);
    });

    Route::prefix('comments')->group(function () {
        Route::get('/{movie}', [CommentsController::class, 'index']);
        Route::post('/', [CommentsController::class, 'store']);
        Route::get('/{comment}', [CommentsController::class, 'show']);
        Route::delete('/{comment}', [CommentsController::class, 'destroy']);
    });

    Route::prefix('movie-characters')->group(function () {
        Route::post('/', [MovieCharactersController::class, 'store']);
        Route::get('/{movie-character}', [MovieCharactersController::class, 'show']);
        Route::put('/{movie-character}', [MovieCharactersController::class, 'update']);
        Route::delete('/{movie-character}', [MovieCharactersController::class, 'destroy']);
        Route::get('/{movie?}/movie', [MovieCharactersController::class, 'index']);
    });

});
