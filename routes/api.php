<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\FedexController;
use App\Http\Controllers\Api\MeliController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\ChildCategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Front\BancardController;
use App\Http\Controllers\Front\Pay42PixController;
use App\Http\Controllers\Front\Pay42BilletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('test', [BancardController::class, 'store']);

Route::middleware(['api.jwt'])->group(function () {
    
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('me', 'me');
    });

    Route::apiResources([
        'categories' => CategoryController::class,
        'sub_categories' => SubCategoryController::class,
        'child_categories' => ChildCategoryController::class,
        'brands' => BrandController::class,
        'orders' => OrderController::class,
    ]);
});

/*
    Public MELI API Routes. This is where Meli sends all notifications for us.
*/
Route::prefix('v1')->controller(MeliController::class)->group(function () {
    Route::get('meli_callback', 'callback');
    Route::post('meli_notifications', 'notifications');
});

Route::prefix('pay42')->group(function () {
    Route::post('pix-notifications', [Pay42PixController::class, 'notify']);
    Route::post('billet-notifications', [Pay42BilletController::class, 'notify']);
});

Route::get('products', [ProductController::class, 'index'])->name('api.products.index');

/* Fedex */
Route::prefix('fedex')->controller(FedexController::class)->group(function () {
    Route::get('auth', 'authorization')->name('fedexauth');
});
