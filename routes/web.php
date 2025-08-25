<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HairstyleController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ServiceController;
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

Route::get('/', fn () => view('welcome'))->name('home');

Route::middleware('auth')->group(function () {

    /** ===================== DASHBOARD ===================== */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** ===================== MENU SCROLL ===================== */
    Route::redirect('/beranda', '/#beranda')->name('beranda');
    Route::redirect('/layanan', '/#layanan')->name('layanan');
    Route::redirect('/tentang', '/#tentang')->name('tentang');
    Route::redirect('/produk', '/#produk')->name('produk');
    Route::redirect('/reservasi', '/#reservasi')->name('reservasi');

    /** ===================== PROFILE ===================== */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Pelanggan Routes
    |--------------------------------------------------------------------------
    | Role: pelanggan
    */
    Route::middleware('role:pelanggan')->group(function () {
        // Booking
        Route::resource('bookings', BookingController::class);

        // Payment
        Route::get('/transaction', [PaymentController::class, 'index'])->name('payment.index');
        Route::get('/transaction/{orderId}', [PaymentController::class, 'show'])->name('payment.show');
        Route::get('/transaction/va/{orderId}', [PaymentController::class, 'showVA']);
        Route::get('/transaction/download/{orderId}', [PaymentController::class, 'downloadReceipt'])->name('transaction.download');
        Route::get('/payment/{id}', [PaymentController::class, 'pay'])->name('payment.pay');

        // Recommendation
        Route::resource('rekomendasi', RecommendationController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Pegawai & Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware(['role:admin|pegawai'])->group(function () {
        // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('bookings', BookingController::class)->names([
            'index' => 'admin.bookings.index',
            'create' => 'admin.bookings.create',
            'store' => 'admin.bookings.store',
            'show' => 'admin.bookings.show',
            'edit' => 'admin.bookings.edit',
            'update' => 'admin.bookings.update',
            'destroy' => 'admin.bookings.destroy',
        ]);
        Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
            ->name('admin.bookings.updateStatus');

        // Common resources for pegawai & admin
        Route::resource('services', ServiceController::class)->names([
            'index' => 'admin.services.index',
            'create' => 'admin.services.create',
            'store' => 'admin.services.store',
            'show' => 'admin.services.show',
            'edit' => 'admin.services.edit',
            'update' => 'admin.services.update',
            'destroy' => 'admin.services.destroy',
        ]);
        Route::resource('hairstyles', HairstyleController::class)->names([
            'index' => 'admin.hairstyles.index',
            'create' => 'admin.hairstyles.create',
            'store' => 'admin.hairstyles.store',
            'show' => 'admin.hairstyles.show',
            'edit' => 'admin.hairstyles.edit',
            'update' => 'admin.hairstyles.update',
            'destroy' => 'admin.hairstyles.destroy',
        ]);
        Route::resource('transactions', TransactionController::class)->names([
            'index' => 'admin.transactions.index',
            'create' => 'admin.transactions.create',
            'store' => 'admin.transactions.store',
            'show' => 'admin.transactions.show',
            'edit' => 'admin.transactions.edit',
            'update' => 'admin.transactions.update',
            'destroy' => 'admin.transactions.destroy',
        ]);
        Route::resource('loyalties', LoyaltyController::class)->names([
            'index' => 'admin.loyalties.index',
            'create' => 'admin.loyalties.create',
            'store' => 'admin.loyalties.store',
            'show' => 'admin.loyalties.show',
            'edit' => 'admin.loyalties.edit',
            'update' => 'admin.loyalties.update',
            'destroy' => 'admin.loyalties.destroy',
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Only Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::resource('roles', RoleController::class)->names([
            'index' => 'admin.roles.index',
            'create' => 'admin.roles.create',
            'store' => 'admin.roles.store',
            'show' => 'admin.roles.show',
            'edit' => 'admin.roles.edit',
            'update' => 'admin.roles.update',
            'destroy' => 'admin.roles.destroy',
        ]);
        // Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);

        // User management extras
        Route::post('/users/{user}/resend-verification', [UserController::class, 'resendVerification'])
            ->name('admin.users.resend-verification');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('admin.users.reset-password');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('admin.users.toggle-status');
        Route::get('/users/stats', [UserController::class, 'getStats'])->name('admin.users.stats');
    });

    /*
    |--------------------------------------------------------------------------
    | Verification Resend
    |--------------------------------------------------------------------------
    */

});

require __DIR__.'/auth.php';
