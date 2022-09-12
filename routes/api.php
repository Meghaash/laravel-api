<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegistrationController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile', [RegistrationController::class, 'getProfile']);
    Route::put('profile', [RegistrationController::class, 'updateProfile']);

    Route::post('post', [PostController::class, 'create']);
    Route::put('post/{id}', [PostController::class, 'update']);
    Route::get('post/{id}', [PostController::class, 'read']);
    Route::delete('post/{id}', [PostController::class, 'delete']);

    Route::post('comment', [CommentController::class, 'create']);
    Route::get('comment', [CommentController::class, 'read']);
});