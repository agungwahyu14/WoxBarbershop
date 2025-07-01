<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
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



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/beranda', function () {
        return redirect('/#beranda');
    })->name('beranda');
    Route::get('/layanan', function () {
        return redirect('/#layanan');
    })->name('layanan');
    Route::get('/tentang', function () {
        return redirect('/#tentang');
    })->name('tentang');
    Route::get('/produk', function () {
        return redirect('/#produk');
    })->name('produk');
    Route::get('/reservasi', function () {
        return redirect('/#reservasi');
    })->name('reservasi');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Tambahkan route admin lainnya di sini
});

require __DIR__ . '/auth.php';
