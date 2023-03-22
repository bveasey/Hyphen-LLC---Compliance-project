<?php

use App\Http\Controllers\API\AccountLookupController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

*/
// User Login Route (public)
Route::post('login', [UserController::class, 'login'])->name('login');

// Brand management (public)
Route::get('brands', [BrandController::class, 'index'])->name('brands.index');

// Authenticated Routes
Route::group(['middleware' => ['token.decrypt', 'auth:sanctum']], function () {
    // Log out route
    Route::post('logout', [UserController::class, 'logout'])->name('logout');

    // User routes
    Route::apiResource('users', UserController::class)->except([
        'created', 'edit',
    ]);
    Route::get('user', [UserController::class, 'getAuthedUser'])->name('user.show');

    // Roles Routes
    Route::get('roles', [RoleController::class, 'index'])->name('role.index');

    // Service
    Route::post('services/{service}/lookup-by-email', [AccountLookupController::class, 'lookupByEmail'])->name('services.accountLookup');

    // Get services list
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');

    // Brand management
    Route::match(['put', 'patch'], 'brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
});
