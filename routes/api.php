<?php

use App\Http\Controllers\AnimalController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/animals/list/', [AnimalController::class, 'list'])->whereNumber("page");
Route::get('/animals/list/{page}', [AnimalController::class, 'list'])->whereNumber("page");
Route::post('/animals', [AnimalController::class, 'form']);
Route::post('/animals/generate', [AnimalController::class, 'generate']);
Route::put('/animals/{id}', [AnimalController::class, 'form'])->whereNumber("id");
Route::delete('/animals/{id}', [AnimalController::class, 'delete'])->whereNumber("id");
