<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CartController;

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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('cart', [CartController::class, 'index']);

Route::middleware(['jwt.verify'])->group(function () {
    Route::get('cart_all', [CartController::class, 'cartAuth']);
    Route::get('user', [UserController::class, 'getAuthenticatedUser']);
});
