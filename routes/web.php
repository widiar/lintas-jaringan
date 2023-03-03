<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index'])->name('home');

Route::get('login', function () {
    return view('auth.login');
})->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::get('register', function () {
    return view('auth.register');
})->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::get('user/activate', [AuthController::class, 'activate'])->name('activate.user');

Route::middleware('auth')->group(function () {
    Route::get('paket/{id}', [SiteController::class, 'paket'])->name('paket');
    Route::post('paket', [SiteController::class, 'beliPaket'])->name('beli.paket');
});

Route::get('thank-you', [SiteController::class, 'thankyou'])->name('thank-you');
Route::get('failed', [SiteController::class, 'failed'])->name('failed');


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
            //manage banner
            Route::controller(BannerController::class)->group(function () {
                Route::prefix('banner/')->group(function () {
                    Route::get('', 'index')->name('banner')->middleware(['permission:view_banner']);
                    Route::get('create', 'create')->name('banner.create')->middleware(['permission:create_banner']);
                    Route::post('store', 'store')->name('banner.store')->middleware(['permission:create_banner']);
                    Route::get('show/{id}', 'show')->name('banner.show')->middleware(['permission:detail_banner']);
                    Route::get('edit/{id}', 'edit')->name('banner.edit')->middleware(['permission:edit_banner']);
                    Route::put('update/{id}', 'update')->name('banner.update')->middleware(['permission:edit_banner']);
                    Route::delete('delete/{id}', 'destroy')->name('banner.destroy')->middleware(['permission:delete_banner']);
                });
            });
            //manage service
            Route::controller(ServiceController::class)->group(function () {
                Route::prefix('service/')->group(function () {
                    Route::get('', 'index')->name('service')->middleware(['permission:view_service']);
                    Route::get('create', 'create')->name('service.create')->middleware(['permission:create_service']);
                    Route::post('store', 'store')->name('service.store')->middleware(['permission:create_service']);
                    Route::get('show/{id}', 'show')->name('service.show')->middleware(['permission:detail_service']);
                    Route::get('edit/{id}', 'edit')->name('service.edit')->middleware(['permission:edit_service']);
                    Route::put('update/{id}', 'update')->name('service.update')->middleware(['permission:edit_service']);
                    Route::delete('delete/{id}', 'destroy')->name('service.destroy')->middleware(['permission:delete_service']);
                });
            });
            //manage paket
            Route::controller(PaketController::class)->group(function () {
                Route::prefix('paket/')->group(function () {
                    Route::get('', 'index')->name('paket')->middleware(['permission:view_paket']);
                    Route::post('checkshow', 'check')->name('paket.check');
                    Route::get('create', 'create')->name('paket.create')->middleware(['permission:create_paket']);
                    Route::post('store', 'store')->name('paket.store')->middleware(['permission:create_paket']);
                    Route::get('show/{id}', 'show')->name('paket.show')->middleware(['permission:detail_paket']);
                    Route::get('edit/{id}', 'edit')->name('paket.edit')->middleware(['permission:edit_paket']);
                    Route::put('update/{id}', 'update')->name('paket.update')->middleware(['permission:edit_paket']);
                    Route::delete('delete/{id}', 'destroy')->name('paket.destroy')->middleware(['permission:delete_paket']);
                });
            });
        });
    });
});
