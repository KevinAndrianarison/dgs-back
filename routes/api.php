<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\TypeMaterielController;
use App\Http\Controllers\Api\SourceController;
use App\Http\Controllers\Api\ReferenceController;
use App\Http\Controllers\Api\MaterielController;
use App\Http\Controllers\Api\SupplyController;
use App\Http\Controllers\Api\VehiculeUtilisationController;
use App\Http\Controllers\Api\AppartenanceController;
use App\Http\Controllers\Api\DetailsSupplyController;
use App\Http\Controllers\Api\HistoriqueController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('getAllUsers', [AuthController::class, 'getAllUsers']);
Route::delete('destroy/{idUser}', [AuthController::class, 'destroy']);

Route::apiResource('regions', RegionController::class);
Route::post('regions/destroy-multiple', [RegionController::class, 'destroyMultiple']);

Route::delete('appartenances/destroy-multiple', [AppartenanceController::class, 'destroyMultiple']);
Route::apiResource('appartenances', AppartenanceController::class);

Route::delete('categories/destroy-multiple', [CategorieController::class, 'destroyMultiple']);
Route::apiResource('categories', CategorieController::class);

Route::delete('types-materiels/destroy-multiple', [TypeMaterielController::class, 'destroyMultiple']);
Route::apiResource('types-materiels', TypeMaterielController::class);

Route::delete('sources/destroy-multiple', [SourceController::class, 'destroyMultiple']);
Route::apiResource('sources', SourceController::class);

Route::delete('references/destroy-multiple', [ReferenceController::class, 'destroyMultiple']);
Route::apiResource('references', ReferenceController::class);

Route::apiResource('materiels', MaterielController::class);
Route::get('materiels/region/{regionId}', [MaterielController::class, 'getMaterielParIdRegion']);
Route::get('materiel/vehicule', [MaterielController::class, 'getAllMaterielVehicule']);
Route::get('vehicules/region/{regionId}', [MaterielController::class, 'getMaterielVehiculeParIdRegion']);
Route::put('materiels/change-id-region/{idMateriel}/{idRegion}', [MaterielController::class, 'changeIdRegion']);

Route::apiResource('supplies', SupplyController::class);
Route::get('supplies/region/{regionId}', [SupplyController::class, 'getByIdRegion']);
Route::put('supplies/add-or-minus/{id}', [SupplyController::class, 'addOrMinusSupply']);
Route::put('supplies/share-to-region/{idRegion}/{idSupply}', [SupplyController::class, 'shareSupplyToRegion']);

Route::apiResource('vehicules', VehiculeUtilisationController::class);

Route::apiResource('details-supplies', DetailsSupplyController::class);

Route::get('vehicules/plannification/{id}', [VehiculeUtilisationController::class, 'getPlannificationByIdMateriel']);

Route::apiResource('historiques', HistoriqueController::class);

Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-photo', [AuthController::class, 'updatePhoto']);
    Route::put('update-user', [AuthController::class, 'updateUser']);
    Route::put('update-password', [AuthController::class, 'updatePassword']);
    Route::post('update-photo', [AuthController::class, 'updatePhoto']);
});
