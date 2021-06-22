<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

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

Route::group(['prefix'=>'auth'],function (){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::group(['middleware'=>'auth:api'],function (){
       Route::get('me',[AuthController::class,'getMe']);
       Route::get('logout',[AuthController::class,'logout']);

    });
});

Route::group(['prefix'=>'products'],function (){
   Route::get('/',[ProductController::class,'index']);
   Route::group(['middleware'=>'auth:api'],function (){
      Route::post('create',[ProductController::class,'create']);
      Route::put('update/{id?}',[ProductController::class,'update']);
      Route::delete('delete/{id?}',[ProductController::class,'delete']);

   });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
