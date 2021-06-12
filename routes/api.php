<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::post('register', [UserController::class, 'addUser']);
Route::post('auth/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:api')->group(function(){
    Route::get('user-details/{id}', [UserController::class, 'showUser'])->name('userDetail');
    Route::put('update-user/{id}', [UserController::class, 'updateUser'])->name('updateUser');
    Route::delete('delete-user/{id}', [UserController::class, 'deleteUser'])->name('deleteUser');
});
