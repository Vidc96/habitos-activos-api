<?php

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

use App\Http\Controllers\BookController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


    // user routes
    Route::get('/users/{id}/admin', [UserController::class, 'index']);
    Route::get('/users/login', [UserController::class, 'login']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}/admin/user', [UserController::class, 'showByEmail']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Goal routes
    Route::get('/goals/{id}', [GoalController::class, 'index']);
    Route::post('/goals/user/{id}', [GoalController::class, 'store']);
    Route::delete('/goals/{id}', [GoalController::class, 'destroy']);
    Route::post('/goals/{id}/check', [GoalController::class, 'checkGoal']);


    // Book routes
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
