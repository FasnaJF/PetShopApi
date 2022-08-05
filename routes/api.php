<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AdminController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\FileController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\BrandController;
use \App\Http\Controllers\PaymentController;
use \App\Http\Controllers\OrderStatusController;

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::post('/create', 'register');
            Route::post('/login', 'login');
            Route::get('/logout', 'logout');

            Route::middleware(['jwtAuth', 'isAdmin'])->group(function () {
                Route::get('/user-listing', 'listAllUsers');
                Route::put('/user-edit/{uuid}', 'updateUser');
                Route::delete('/user-delete/{uuid}', 'destroyUser');
            });
        });
    });

    Route::prefix('user')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::post('/create', 'createUser');
            Route::post('/login', 'login');
            Route::get('/logout', 'logout');
            Route::post('/forgot-password', 'forgotPassword');
            Route::post('/reset-password-token', 'resetPasswordToken');

            Route::middleware(['jwtAuth'])->group(function () {
                Route::get('', 'getUser');
                Route::get('/orders', 'getUserOrders');
                Route::put('/edit', 'updateUser');
                Route::delete('', 'destroy');
            });
        });
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index');
        Route::get('/category/{uuid}', 'show');
        Route::middleware(['jwtAuth'])->group(function () {
            Route::post('/category/create', 'create');
            Route::put('/category/{uuid}', 'update');
            Route::delete('/category/{uuid}', 'destroy');
        });
    });

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index');
        Route::get('/brand/{uuid}', 'show');
        Route::middleware(['jwtAuth'])->group(function () {
            Route::post('/brand/create', 'create');
            Route::put('/brand/{uuid}', 'update');
            Route::delete('/brand/{uuid}', 'destroy');
        });
    });

    Route::controller(OrderController::class)->group(function () {
        Route::middleware(['jwtAuth'])->group(function () {
            Route::get('/orders', 'index');
            Route::get('/order/{uuid}', 'show');
            Route::post('/order/create', 'create');
            Route::put('/order/{uuid}', 'update');
            Route::delete('/order/{uuid}', 'destroy');
            Route::middleware(['isAdmin'])->group(function () {
                Route::get('/order/{uuid}/download', 'downloadOrder');
                Route::get('/orders/dashboard', 'dashboard');
                Route::get('/orders/shipment-locator', 'shipmentLocator');
            });
        });
    });

    Route::controller(OrderStatusController::class)->group(function () {
        Route::get('/order-statuses', 'index');
        Route::get('/order-status/{uuid}', 'show');
        Route::middleware(['jwtAuth'])->group(function () {
            Route::post('/order-status/create', 'create');
            Route::put('/order-status/{uuid}', 'update');
            Route::delete('/order-status/{uuid}', 'destroy');
        });
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::middleware(['jwtAuth'])->group(function () {
            Route::get('/payments', 'index');
            Route::get('/payment/{uuid}', 'show');
            Route::post('/payment/create', 'create');
            Route::put('/payment/{uuid}', 'update');
            Route::delete('/payment/{uuid}', 'destroy');
        });
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/product/{uuid}', 'show');
        Route::middleware(['jwtAuth'])->group(function () {
            Route::post('/product/create', 'create');
            Route::put('/product/{uuid}', 'update');
            Route::delete('/product/{uuid}', 'destroy');
        });
    });

    Route::controller(FileController::class)->group(function () {
        Route::get('/file/{uuid}', 'show');
        Route::middleware(['jwtAuth'])->group(function () {
            Route::post('/file/upload', 'upload');
        });
    });
});
