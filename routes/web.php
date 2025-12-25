<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Controllers\ComplaintController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');


        Route::patch(
            '/complaints/{complaint}/update-status',
            [ComplaintController::class, 'updateStatus']
        )->name('complaints.update-status');

        Route::middleware(['verified'])->group(function () {
            Route::resource('/complaints', ComplaintController::class);
        });
    });

    
Route::middleware(['auth', 'verified', UserMiddleware::class])
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    
    });