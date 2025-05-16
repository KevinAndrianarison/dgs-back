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
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\TypeMaterielController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\SourceController;
use App\Http\Controllers\Api\ReferenceController;
use App\Http\Controllers\Api\MaterielController;
use App\Http\Controllers\Api\SupplyController;
use App\Http\Controllers\Api\VehiculeUtilisationController;



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
Route::apiResource('admins', AdminController::class); 
Route::apiResource('roles', RoleController::class);
Route::post('admins/{adminId}/roles', [AdminController::class, 'attachRole']);
Route::post('roles/{roleId}/attach-admin', [RoleController::class, 'attachAdmin']);
Route::delete('admins/{adminId}/roles/{roleId}', [AdminController::class, 'detachRole']);
Route::get('admins/{adminId}/roles', [AdminController::class, 'getUserRoles']);
Route::get('roles/{roleId}/admins', [RoleController::class, 'getRoleUsers']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('getAllUsers', [AuthController::class, 'getAllUsers']);
Route::delete('destroy/{idUser}', [AuthController::class, 'destroy']);


Route::apiResource('regions', RegionController::class);
Route::post('regions/destroy-multiple', [RegionController::class, 'destroyMultiple']);

Route::delete('categories/destroy-multiple', [CategorieController::class, 'destroyMultiple']);
Route::apiResource('categories', CategorieController::class);

Route::delete('types/destroy-multiple', [TypeController::class, 'destroyMultiple']);
Route::apiResource('types', TypeController::class);

Route::delete('types-materiels/destroy-multiple', [TypeMaterielController::class, 'destroyMultiple']);
Route::apiResource('types-materiels', TypeMaterielController::class);
Route::get('materiels/region/{regionId}', [MaterielController::class, 'getMaterielParIdRegion']);

Route::delete('sources/destroy-multiple', [SourceController::class, 'destroyMultiple']);
Route::apiResource('sources', SourceController::class);

Route::delete('references/destroy-multiple', [ReferenceController::class, 'destroyMultiple']);
Route::apiResource('references', ReferenceController::class);
Route::apiResource('materiels', MaterielController::class);
Route::apiResource('supplies', SupplyController::class);
Route::apiResource('vehicules', VehiculeUtilisationController::class);


Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-photo', [AuthController::class, 'updatePhoto']);
    Route::put('update-user', [AuthController::class, 'updateUser']);
    Route::put('update-password', [AuthController::class, 'updatePassword']);
    Route::post('update-photo', [AuthController::class, 'updatePhoto']);
    // Route::apiResource('admins', AdminController::class); 
});
