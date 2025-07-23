<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\VehicleController;

Route::prefix('auth')->name('auth.')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        // Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::get('/user-profile', [AuthController::class, 'userProfile'])->name('profile');

        Route::post('create-car', [CarController::class, 'createCar'])->name('car.create');
        Route::get('get-all-cars', [CarController::class, 'getAllCars'])->name('car.all');
        Route::put('edit-car/{id}', [CarController::class, 'editCar'])->name('car.edit');
        Route::get('get-spesific-car/{id}', [CarController::class, 'getSpesificCar'])->name('car.show');
        Route::get('delete-car/{id}', [CarController::class, 'deleteCar'])->name('car.delete');
        Route::get('restore-car/{id}', [CarController::class, 'restoreCar'])->name('car.restore');
        Route::get('force-delete-car/{id}', [CarController::class, 'forceDeleteCar'])->name('car.forceDelete');
        Route::get('get-soft-deleted-cars', [CarController::class, 'getSofDeletedCar'])->name('car.softDeleted');

        Route::get('get-all-rentals', [RentalController::class, 'getAllRentals'])->name('rental.all');
        Route::post('create-rental', [RentalController::class, 'createRental'])->name('rental.create');

        // Route::get('get-all-categories', [CategoryController::class, 'allCategories'])->name('category.all');
        // Route::post('create-category', [CategoryController::class, 'createCategory'])->name('category.create');
        // Route::put('edit-category/{id}', [CategoryController::class, 'updateCategory'])->name('category.edit');
        // Route::get('soft-delete-category/{id}', [CategoryController::class, 'softDeleteCategory'])->name('category.softDelete');
        // Route::get('get-soft-deleted-categories', [CategoryController::class, 'getSoftDeletedCategories'])->name('category.softDeleted');
        // Route::get('restore-category/{id}', [CategoryController::class, 'restoreCategory'])->name('category.restore');
        // Route::get('force-delete-category/{id}', [CategoryController::class, 'forceDeleteCategory'])->name('category.forceDelete');
        // Route::get('dropdown-category', [CategoryController::class, 'dropDownCategory'])->name('category.dropdown');
        Route::get('categories', [CategoryController::class, 'index']);
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories', [CategoryController::class, 'update']);

        Route::get('get-all-vehicles', [VehicleController::class, 'getAllVehicles'])->name('vehicle.all');
        Route::post('create-vehicle', [VehicleController::class, 'createVehicle'])->name('vehicle.create');
        Route::get('delete-vehicle/{id}', [VehicleController::class, 'softDeleteVehicle'])->name('vehicle.softDelete');
    });
});
