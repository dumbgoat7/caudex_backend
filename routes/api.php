<?php

use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//tambah route baru
Route::apiResource('/authors', App\Http\Controllers\Api\AuthorController::class);
Route::apiResource('/books', App\Http\Controllers\Api\BookController::class);
Route::apiResource('/categories', App\Http\Controllers\Api\CategoryController::class);
Route::apiResource('/publishers', App\Http\Controllers\Api\PublisherController::class);
Route::apiResource('/users', App\Http\Controllers\Api\UserController::class);
Route::apiResource('/reads', App\Http\Controllers\Api\ReadController::class);
Route::apiResource('/Reviews', App\Http\Controllers\Api\ReviewController::class);
Route::apiResource('/Subscribers', App\Http\Controllers\Api\SubscriberController::class);
Route::apiResource('/subscriptionhistories', App\Http\Controllers\Api\SubscriptionHistoryController::class);

// Route::get('dashboard', [AuthController::class, 'dashboard'])->middleware(['auth', 'is_verify_email']); 
Route::get('account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('user.verify');
