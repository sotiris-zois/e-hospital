<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SpecializationsController;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class,'currentUser']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::get('register',[AuthController::class,'registerUser']);
});
Route::get('search-specialties', [SpecializationsController::class, 'search']);
Route::post('login', [AuthController::class, 'login']);
