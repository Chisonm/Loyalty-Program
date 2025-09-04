<?php

declare(strict_types=1);

use App\Http\Controllers\APIs\Auth\LoginController;
use App\Http\Controllers\APIs\Checkout\CheckoutController;
use App\Http\Controllers\APIs\Users\UserAchievementsController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', function () {
    return response()->json([
       'users' => User::all()
    ], 200);
});
Route::get('/users/{user}', function (User $user) {
    return response()->json([
       'users' => $user
    ], 200);
});
Route::get('/users/{user}/achievements', UserAchievementsController::class);
Route::post('/login', LoginController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', CheckoutController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
