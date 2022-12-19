<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\BarangController;

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

Route::middleware(['jwt.verify'])->group(function () {
    Route::get('user', [UserController::class, 'getAuthenticatedUser']);

    Route::apiResource('barang', BarangController::class)
    ->except(['create','edit','show']);

    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart', [CartController::class, 'store']);
    Route::put('cart/update', [CartController::class, 'update']);
    Route::post('cart/empty', [CartController::class, 'empty']);
    Route::post('cart/checkout', [CartController::class, 'checkout']);
});
