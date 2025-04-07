<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LoginCanalController;
use App\Http\Controllers\Api\PromoApiController;
use App\Http\Controllers\Api\ProdApiController;
use App\Http\Controllers\Api\ImagenApiController;
use App\Http\Controllers\Api\SyncproductController;
use App\Http\Controllers\Api\ActOtpApiController;
use App\Http\Controllers\Api\PasswordResetController;

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

Route::post('/loginCanal', [LoginCanalController::class, 'credencialLocal']);

Route::post('/login', [LoginController::class, 'credencial']);

Route::post('/promocion', [PromoApiController::class, 'promo']);

Route::post('/producto', [ProdApiController::class, 'producto']);

Route::post('/imagen', [ImagenApiController::class, 'cargaimagen']);

Route::post('/syncpro',[SyncproductController::class, 'rel_categ']);

Route::post('/consultatodo',[ProdApiController::class, 'totalproduct']);

Route::post('/clasificacion_pro',[ProdApiController::class, 'clasificacion']);

Route::post('/actualizarDfa',[ActOtpApiController::class, 'actualizarOtp']);

Route::post('/password/reset-code', [PasswordResetController::class, 'sendResetCode']);

Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);