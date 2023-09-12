<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SpecializationsController;
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

    Route::get('/user', function (Request $request) {

        $requestedUser = $request->user();

        $authUser = auth()->user();

        if ($requestedUser->id === $authUser->id) {
            return [
                'user' => [
                    'username' => $authUser->username,
                    'token' => $request->bearerToken()
                ],
            ];
        }
    });
    Route::post('logout',[AuthController::class,'logout']);
});
Route::get('search-specialties', [SpecializationsController::class, 'search']);
Route::post('login', [AuthController::class, 'login']);
