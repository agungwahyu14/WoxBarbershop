<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HairstyleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TransactionController;
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

    // Dashboard Routes
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

    // End of Dashboard Routes

    // Recccommendation Routes
    Route::get('/rekomendasi', function () {
        return view('rekomendasi');
    })->name('rekomendasi');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('bookings', BookingController::class);
    Route::resource('transactions', TransactionController::class);
    // End of Profile Routes
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('hairstyles', HairstyleController::class);

    // Tambahkan route admin lainnya di sini
});

require __DIR__ . '/auth.php';
