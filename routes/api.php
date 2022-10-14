<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Threads\ThreadController;
use App\Http\Controllers\V1\Threads\UserThreadMessageController;
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

/* Auth routes */
Route::group(['prefix' => 'v1/auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.v1.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
});

/* Auth routes with authentication */
Route::group(['prefix' => 'v1/auth', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/{user}', [AuthController::class, 'getUser'])->name('api.v1.auth.getUser');
});

/* Thread routes with authentication */
Route::group(['prefix' => 'v1/threads', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [ThreadController::class, 'create'])->name('api.v1.threads.create');
    Route::get('/{thread}/messages', [ThreadController::class, 'getMessages'])->name('api.v1.threads.getMessages');
    Route::post('/{thread}/messages/search', [ThreadController::class, 'searchUserThreadMessages'])->name('api.v1.threads.searchUserThreadMessages');
});

/* UserThreadMessage routes with authentication */
Route::group(['prefix' => 'v1/user', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/{user}/threads', [UserThreadMessageController::class, 'getUserThreads'])->name('api.v1.userThreadMessage.getUserThreads');
    Route::post('/{user}/threads/{thread}/messages', [UserThreadMessageController::class, 'createMessage'])->name('api.v1.userThreadMessage.createMessage');
    Route::patch('/messages/{message}', [UserThreadMessageController::class, 'updateMessage'])->name('api.v1.userThreadMessage.updateMessage');
});
