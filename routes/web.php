<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::name('dashboard.')->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::controller(DashboardController::class)->group(function () {
                Route::get('/', 'index')->name('index');
            });
            Route::name('bookings.')->group(function () {
                Route::prefix('bookings')->group(function () {
                    Route::controller(BookingController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/create', 'create')->name('create');
                    });
                });
            });
            Route::name('vehicles.')->group(function () {
                Route::prefix('vehicles')->group(function () {
                    Route::controller(VehicleController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                    });
                });
            });
        });
    });
});
