<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\AuthorizationController;

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
    Route::post('register',[AuthorizationController::class, 'register']);
    Route::post('edituser',[UserController::class, 'editUser']);
    Route::post('login',[AuthorizationController::class, 'login']);
    Route::post('logout',[AuthorizationController::class, 'logout']);
    Route::post('addcountry',[CountryController::class, 'addcountry']);
    Route::post('addrole',[RolesController::class, 'addrole']);
    Route::get('country',[CountryController::class, 'getAllCountry']);
    Route::get('roles',[RolesController::class, 'getAllRoles']);
    Route::get('getAllUser',[UserController::class, 'getAllUser']);
    Route::get('getCurrentUser',[UserController::class, 'getCurrentUser']);
    Route::post('delete',[UserController::class,'deleteUser']);
});




