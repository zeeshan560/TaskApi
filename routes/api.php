<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('login', 'login');
//Route::post('register', 'register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/task', [TaskController::class, 'index']);
Route::middleware('auth:sanctum')->get('/getTask/{id}',[TaskController::class,'show']);
Route::middleware('auth:sanctum')->post('/createTask', [TaskController::class, 'store']);
Route::middleware('auth:sanctum')->post('/updateTask/{id}',[TaskController::class,'update']);
Route::middleware('auth:sanctum')->put('/deleteTask/{id}',[TaskController::class,'destroy']);
