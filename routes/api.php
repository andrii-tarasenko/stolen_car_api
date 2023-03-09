<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CarController;

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

Route::post('/add/stolen_cars', [CarController::class, 'store']);
Route::get('/get/stolen_cars', [CarController::class, 'index']);
Route::put('/update/stolen_cars/{id}', [CarController::class, 'update']);
Route::delete('/delete/stolen_cars/{id}', [CarController::class, 'destroy']);
Route::get('/export/stolen_cars', [CarController::class, 'export']);
Route::get('/autocomplete', [CarController::class, 'autocomplete']);
Route::get('/makes', [CarController::class, 'getMakes']);
Route::get('/makes/{make_id}/models', [CarController::class, 'getModels']);

