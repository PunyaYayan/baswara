<?php

use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
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


Route::get('products', [ProductController::class, 'all']);
Route::get('categories', [ProductCategoryController::class, 'all']);

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // products
    Route::post('products', [ProductController::class, 'addProduct']);
    Route::delete('products', [ProductController::class, 'deleteProduct']);
    // post categories
    Route::post('categories', [ProductCategoryController::class, 'addCategories']);

    Route::get('user', [UserController::class, 'fetch']);

    Route::get('admin', [UserController::class, 'admin']);
    // update
    Route::post('user', [UserController::class, 'updateProfile']);
    // logout
    Route::post('logout', [UserController::class, 'logout']);
    
    // transaction
    Route::get('transaction', [TransactionController::class, 'all']);
    Route::post('checkout', [TransactionController::class, 'checkout']);
    
});