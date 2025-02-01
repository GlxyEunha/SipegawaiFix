<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin_sdm'])->group(function () {
    Route::get('/admin-sdm/dashboard', function () {
        return view('dashboard.admin_sdm');
    })->name('admin_sdm.dashboard');

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
});

Route::middleware(['auth', 'role:admin_user'])->group(function () {
    Route::get('/admin-user/dashboard', function () {
        return view('dashboard.admin_user');
    })->name('admin_user.dashboard');
});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/pegawai/dashboard', function () {
        return view('dashboard.pegawai');
    })->name('pegawai.dashboard');
});

Route::middleware(['auth', 'role:pemutus'])->group(function () {
    Route::get('/pemutus/dashboard', function () {
        return view('dashboard.pemutus');
    })->name('pemutus.dashboard');
});