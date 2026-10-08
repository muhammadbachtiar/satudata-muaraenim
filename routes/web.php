<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CkanController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NavMenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\ProdukStatistikOpdController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [CkanController::class, 'index'])->name('home');
Route::get('/dataset', [CkanController::class, 'search'])->name('datasets.search');
Route::get('/dataset/{id}', [CkanController::class, 'datasetShow'])->name('datasets.show');
Route::get('/instansi', [CkanController::class, 'organizations'])->name('organizations');
Route::get('/resource/{id}', [CkanController::class, 'resourceShow'])->name('resources.show');
Route::get('/publikasi', [CkanController::class, 'publikasi'])->name('publikasi');
Route::get('/publikasi/produk-statistik-opd', [CkanController::class, 'produkStatistikOpd'])->name('produk-statistik-opd');
Route::get('/publikasi/{slug}', [CkanController::class, 'publikasiShow'])->name('publikasi.show');
Route::get('/tentang', [CkanController::class, 'tentang'])->name('tentang');
Route::get('/halaman/{slug}', [CkanController::class, 'pageShow'])->name('page.show');

/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Panel (auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('nav-menus', NavMenuController::class)->except(['show']);
    Route::resource('pages', PageController::class)->except(['show']);
    Route::resource('publications', PublicationController::class)->except(['show']);
    Route::get('produk-statistik-opd', [ProdukStatistikOpdController::class, 'index'])->name('produk-statistik-opd.index');
    Route::post('produk-statistik-opd', [ProdukStatistikOpdController::class, 'store'])->name('produk-statistik-opd.store');
    Route::delete('produk-statistik-opd/{filename}', [ProdukStatistikOpdController::class, 'destroy'])->name('produk-statistik-opd.destroy');
});
