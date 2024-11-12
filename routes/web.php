<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingApprovalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
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
    Route::middleware(['role:1|2'])->group(function () {
        Route::name('dashboard.')->group(function () {
            Route::prefix('dashboard')->group(function () {
                Route::controller(DashboardController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/get-chart', 'getChart')->name('get-chart');
                });
                Route::middleware(['role:1'])->group(function () {
                    Route::name('bookings.')->group(function () {
                        Route::prefix('bookings')->group(function () {
                            Route::controller(BookingController::class)->group(function () {
                                Route::get('/', 'index')->name('index');
                                Route::get('/create', 'create')->name('create');
                                Route::get('/get-approver', 'getApprover')->name('approver');
                                Route::post('/store', 'store')->name('store');
                                Route::post('/set-done', 'setDone')->name('set-done');
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

                    Route::name('employees.')->group(function () {
                        Route::prefix('employees')->group(function () {
                            Route::controller(EmployeeController::class)->group(function () {
                                Route::get('/', 'index')->name('index');
                            });
                        });
                    });

                    Route::name('drivers.')->group(function () {
                        Route::prefix('drivers')->group(function () {
                            Route::controller(DriverController::class)->group(function () {
                                Route::get('/', 'index')->name('index');
                            });
                        });
                    });
                    Route::name('reports.')->group(function () {
                        Route::prefix('reports')->group(function () {
                            Route::controller(ReportController::class)->group(function () {
                                Route::get('/', 'index')->name('index');
                            });
                        });
                    });
                });

                Route::middleware(['role:2'])->group(function () {
                    Route::name('approvals.')->group(function () {
                        Route::prefix('approvals')->group(function () {
                            Route::controller(BookingApprovalController::class)->group(function () {
                                Route::get('/', 'index')->name('index');
                                Route::post('/approve', 'approve')->name('approve');
                            });
                        });
                    });
                });
            });
        });
    });
});
