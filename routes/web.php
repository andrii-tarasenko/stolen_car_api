<?php

use App\Http\Controllers\API\CarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form_add_cars', function () {
    return view('add-car');
})->name('addCar');

//Route::post('/api/add_cars', [App\Http\Controllers\CarController::class, 'create']);
//Route::get('/api/get_cars', [App\Http\Controllers\CarController::class, 'index']);

//Route::post('/api/add/stolen_cars', [CarController::class, 'store']);
//Route::get('/api/get/stolen_cars', [CarController::class, 'index']);
//Route::put('/api/update/stolen_cars/{id}', [CarController::class, 'update']);
//Route::delete('/api/delete/stolen_cars/{id}', [CarController::class, 'destroy']);
//Route::get('/api/export/stolen_cars', [CarController::class, 'export']);
//Route::get('/api/autocomplete', [CarController::class, 'autocomplete']);
//Route::get('/api/makes', [CarController::class, 'getMakes']);
//Route::get('/api/makes/{make_id}/models', [CarController::class, 'getModels']);


