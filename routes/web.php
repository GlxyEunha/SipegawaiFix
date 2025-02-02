<?php

use App\Http\Controllers\GajiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RollingController;
use App\Http\Controllers\AdminSdmController;

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


Route::middleware(['auth', 'role:admin_sdm'])->group(function () {
    Route::get('/admin-sdm/dashboard', [AdminSdmController::class, 'index'])->name('admin_sdm.dashboard');
    Route::get('/admin/rolling', [AdminSdmController::class, 'rolling'])->name('admin_sdm.rolling');
    Route::get('/admin/pegawai/{nip}/history', [AdminSdmController::class, 'history'])->name('pegawai.history');
    Route::get('/admin/pegawai/{nip}/rolling', [AdminSdmController::class, 'edit'])->name('pegawai.rolling.form'); // Menampilkan form rolling
    Route::post('/admin/pegawai/{nip}/rolling', [AdminSdmController::class, 'update'])->name('pegawai.rolling'); // Simpan perubahan rolling unit
    
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    
    Route::get('/data_pegawai', [AdminSdmController::class, 'index'])->name('datapegawai.index');

    Route::get('/rolling', [RollingController::class, 'index'])->name('rolling.index');
    Route::post('/rolling/store', [RollingController::class, 'store'])->name('rolling.store');
    Route::get('/hasil', [RollingController::class, 'hasil'])->name('rolling.hasil');
    Route::post('/hasil/accept/{nip}', [RollingController::class, 'accept'])->name('rolling.accept');
    Route::get('/hasil/export', [RollingController::class, 'export'])->name('rolling.export');

    Route::get('/gaji', [GajiController::class, 'index'])->name('gaji.index');
    Route::post('/gaji/accept/{nip}', [GajiController::class, 'setujui'])->name('gaji.setujui');
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

require __DIR__.'/auth.php';