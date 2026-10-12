<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('categories', \App\Http\Controllers\API\CategoryController::class);
    Route::apiResource('products', \App\Http\Controllers\API\ProductController::class);
});
