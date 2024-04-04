<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreteUserController;
use App\Http\Controllers\LoginController;
use \App\Http\Controllers\CreateTaskController;
use \App\Http\Middleware\TestAuth;
use \App\Http\Controllers\DeleteTaskController;
use \App\Http\Controllers\UpdateTaskController;
use \App\Http\Controllers\AssignUsersController;

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
    Route::middleware('auth:sanctum')->group(function (){
        Route::post('create', CreateTaskController::class)->name('create');
        Route::prefix('{uuid}')->group(function (){
            Route::post('/delete', DeleteTaskController::class)->name('delete');
            Route::post('/update', UpdateTaskController::class)->name('update');
            Route::post('/assign', AssignUsersController::class)->name('assign');
        });
    });
});
