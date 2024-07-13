<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/authors', [AuthorController::class, 'index']);
Route::post('/authors', [AuthorController::class, 'store']);
Route::get('/authors/{id}', [AuthorController::class, 'show']);
Route::put('/authors/{id}', [AuthorController::class, 'update']);
Route::delete('/authors/{id}', [AuthorController::class, 'destroy']);


Route::apiResource('products', ProductController::class);


Route::apiResource('files', FileController::class);



Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);


Route::apiResource('admins', AdminController::class);   // Sin pas de JWT
Route::apiResource('roles', RoleController::class);

Route::post('admins/{adminId}/roles', [AdminController::class, 'attachRole']);
Route::post('roles/{roleId}/attach-admin', [RoleController::class, 'attachAdmin']);
Route::delete('admins/{adminId}/roles/{roleId}', [AdminController::class, 'detachRole']);
Route::get('admins/{adminId}/roles', [AdminController::class, 'getUserRoles']);
Route::get('roles/{roleId}/admins', [RoleController::class, 'getRoleUsers']);



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('profil', [AuthController::class, 'profil']);
    Route::post('logout', [AuthController::class, 'logout']);
    // Route::apiResource('admins', AdminController::class);    // Si avec JWT

});
