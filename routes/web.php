<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('site.index');
})->name('home');

Route::get('login', function () {
    return view('auth.login');
})->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::name('admin.')->group(function () {
            //dashboard
            Route::get('dashboard', [DashboardController::class, 'admin'])->name('dashboard');

            // manage users
            Route::controller(UserController::class)->group(function () {
                Route::prefix('users/')->group(function () {
                    Route::get('', 'index')->name('user');
                    Route::get('create', 'create')->name('user.create');
                    Route::post('store', 'store')->name('user.store');
                    Route::get('show/{id}', 'show')->name('user.show');
                    Route::get('edit/{id}', 'edit')->name('user.edit');
                    Route::put('update/{id}', 'update')->name('user.update');
                    Route::delete('delete/{id}', 'delete')->name('user.destroy');
                });
            });
            //manage roles
            Route::controller(RoleUserController::class)->group(function () {
                Route::prefix('roles/users/')->group(function () {
                    Route::get('', 'index')->name('roles');
                    Route::get('create', 'create')->name('roles.create');
                    Route::post('store', 'store')->name('roles.store');
                    Route::get('show/{id}', 'show')->name('roles.show');
                    Route::get('edit/{id}', 'edit')->name('roles.edit');
                    Route::put('update/{id}', 'update')->name('roles.update');
                    Route::delete('delete/{id}', 'delete')->name('roles.destroy');
                });
            });
        });
    });
});
