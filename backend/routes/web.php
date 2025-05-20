<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// use App\Http\Controllers\Auth\ResetPasswordController;

// Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::get('/reset-password', function () {
    return file_get_contents(public_path('test.html'));
})->name('password.reset');

Route::get('/{any}', function () {
    return file_get_contents(public_path('test.html'));
})->where('any', '.*');