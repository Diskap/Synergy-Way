<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});


Route::group(['auth:sanctum'], function()
{
    Route::post('register',[UserController::class, 'register']);
    Route::post('edituser',[UserController::class, 'editUser']);
    Route::post('login',[UserController::class, 'login']);
    Route::post('logout',[UserController::class, 'logout']);
    Route::post('addcountry',[UserController::class, 'addcountry']);
    Route::post('addrole',[UserController::class, 'addrole']);
    Route::get('country',[UserController::class, 'getAllCountry']);
    Route::get('roles',[UserController::class, 'getAllRoles']);
    Route::get('getAllUser',[UserController::class, 'getAllUser']);
    Route::get('getCurrentUser',[UserController::class, 'getCurrentUser']);
    Route::post('delete',[UserController::class,'deleteUser']);
});




