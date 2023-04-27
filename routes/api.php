<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function PHPSTORM_META\type;

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

Route::controller(AuthController::class)->prefix('user')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::post('logout', 'logout');
    });

    Route::controller(TaskController::class)->prefix('task')->group(function () {
        Route::post('list', 'list')->middleware('checkRole:Super admin|Admin|Team leader|Employee');
        Route::post('create', 'create')->middleware('checkRole:Team leader');
        Route::get('get/{id}', 'get')->middleware('checkRole:Employee');
        Route::put('update/{id}', 'update')->middleware('checkRole:Team leader|Employee');
        Route::delete('delete/{id}', 'delete')->middleware('checkRole:Team leader');
    });
});
