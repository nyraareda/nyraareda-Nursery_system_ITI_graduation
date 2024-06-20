<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChildrenController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SiblingController;
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

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('email/verify/{id}', [AuthController::class, 'verify'])->name('verification.verify');
});

Route::get('/children', [ChildrenController::class, 'index']);
Route::get('/children/{id}', [ChildrenController::class, 'show']);
Route::post('/children', [ChildrenController::class, 'store']);
Route::put('/children/{id}', [ChildrenController::class, 'update']);
Route::delete('/children/{id}', [ChildrenController::class, 'destroy']);

Route::get('/applications', [ApplicationController::class, 'index']);
Route::get('/applications/{id}', [ApplicationController::class, 'show']);
Route::post('/applications', [ApplicationController::class, 'store']);
Route::put('/applications/{id}', [ApplicationController::class, 'update']);
Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);



// Sibling routes
Route::get('siblings', [SiblingController::class, 'index']);
Route::get('siblings/{id}', [SiblingController::class, 'show']);
Route::post('siblings', [SiblingController::class, 'store']);
Route::put('siblings/{id}', [SiblingController::class, 'update']);
Route::delete('siblings/{id}', [SiblingController::class, 'destroy']);

// Notification routes
Route::get('notifications', [NotificationController::class, 'index']);
Route::get('notifications/{id}', [NotificationController::class, 'show']);
Route::post('notifications', [NotificationController::class, 'store']);
Route::put('notifications/{id}', [NotificationController::class, 'update']);
Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);