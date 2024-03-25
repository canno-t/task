<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreteUserController;
use App\Http\Controllers\LoginController;
use \App\Http\Controllers\CreateTaskController;
use \App\Http\Middleware\TestAuth;
use \App\Http\Controllers\DeleteTaskController;

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

Route::prefix('user')->name('user.')->group(function(){
    Route::post('create', CreteUserController::class)->name('create');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('testauth', function (Request $request){
        return true;
    })->middleware('auth:sanctum')->name('test');
});

Route::prefix('task')->name('task.')
    ->middleware(TestAuth::class)->group(function (){
    Route::post('create', CreateTaskController::class)->name('create');
    Route::post('/{uuid}/delete', DeleteTaskController::class)->middleware('auth:sanctum')->name('delete');
});
