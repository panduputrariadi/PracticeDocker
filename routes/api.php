<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RentalController;

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

        Route::get('get-all-categories', [CategoryController::class, 'allCategories'])->name('category.all');
        Route::post('create-category', [CategoryController::class, 'createCategory'])->name('category.create');
    });
});
