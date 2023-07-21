<?php

use App\Http\Controllers\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('expenses/create', [\App\Http\Controllers\ExpenseController::class, 'create']);
    Route::get('expenses', [\App\Http\Controllers\ExpenseController::class, 'getDailyExpenses']);
    Route::get('user', static fn (Request $request) =>  $request->user());
    Route::post('profile/update', \App\Http\Controllers\V1\UserProfileController::class);
});

