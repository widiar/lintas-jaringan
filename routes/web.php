<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
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

Route::get('register', function () {
    return view('auth.register');
})->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');


Route::middleware(['auth', 'notPelanggan'])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::name('admin.')->group(function () {
            //dashboard
            Route::get('dashboard', [DashboardController::class, 'admin'])->name('dashboard')->middleware(['permission:view_dahsboard']);

            Route::middleware(['role:super admin'])->group(function () {
                Route::get('permission/create', [PermissionController::class, 'create'])->name('permission.create');
                Route::post('permission/create', [PermissionController::class, 'store'])->name('permission.store');
            });

            // manage users
            // Route::resource('user', UserController::class);
            Route::controller(UserController::class)->group(function () {
                Route::prefix('users/')->group(function () {
                    Route::get('', 'index')->name('user')->middleware(['permission:view_user']);
                    Route::get('create', 'create')->name('user.create')->middleware(['permission:create_user']);
                    Route::post('store', 'store')->name('user.store')->middleware(['permission:create_user']);
                    Route::get('show/{id}', 'show')->name('user.show')->middleware(['permission:detail_user']);
                    Route::get('edit/{id}', 'edit')->name('user.edit')->middleware(['permission:edit_user']);
                    Route::put('update/{id}', 'update')->name('user.update')->middleware(['permission:edit_user']);
                    Route::delete('delete/{id}', 'destroy')->name('user.destroy')->middleware(['permission:delete_user']);
                });
            });
            //manage roles
            Route::controller(RoleUserController::class)->group(function () {
                Route::prefix('roles/users/')->group(function () {
                    Route::get('', 'index')->name('roles')->middleware(['permission:view_role_user']);
                    Route::get('create', 'create')->name('roles.create')->middleware(['permission:create_role_user']);
                    Route::post('store', 'store')->name('roles.store')->middleware(['permission:create_role_user']);
                    Route::get('show/{id}', 'show')->name('roles.show')->middleware(['permission:detail_role_user']);
                    Route::get('edit/{id}', 'edit')->name('roles.edit')->middleware(['permission:edit_role_user']);
                    Route::put('update/{id}', 'update')->name('roles.update')->middleware(['permission:edit_role_user']);
                    Route::delete('delete/{id}', 'delete')->name('roles.destroy')->middleware(['permission:delete_role_user']);
                });
            });
        });
    });
});
