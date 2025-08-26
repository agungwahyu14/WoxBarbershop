<?php

use App\Http\Controllers\Admin\HairstyleController as AdminHairstyleController;
use App\Http\Controllers\Admin\LoyaltyController as AdminLoyaltyController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommendationController;
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
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');

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
    | Pegawai 
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
        Route::resource('services', AdminServiceController::class)->names([
            'index' => 'admin.services.index',
            'create' => 'admin.services.create',
            'store' => 'admin.services.store',
            'show' => 'admin.services.show',
            'edit' => 'admin.services.edit',
            'update' => 'admin.services.update',
            'destroy' => 'admin.services.destroy',
        ]);
        Route::resource('hairstyles', AdminHairstyleController::class)->names([
            'index' => 'admin.hairstyles.index',
            'create' => 'admin.hairstyles.create',
            'store' => 'admin.hairstyles.store',
            'show' => 'admin.hairstyles.show',
            'edit' => 'admin.hairstyles.edit',
            'update' => 'admin.hairstyles.update',
            'destroy' => 'admin.hairstyles.destroy',
        ]);
        Route::resource('bookings', BookingController::class)->names([
            'index' => 'admin.bookings.index',
            'create' => 'admin.bookings.create',
            'store' => 'admin.bookings.store',
            'show' => 'admin.bookings.show',
            'edit' => 'admin.bookings.edit',
            'update' => 'admin.bookings.update',
            'destroy' => 'admin.bookings.destroy',
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
        Route::resource('loyalty', AdminLoyaltyController::class)->names([
            'index' => 'admin.loyalty.index',
            'create' => 'admin.loyalty.create',
            'store' => 'admin.loyalty.store',
            'show' => 'admin.loyalty.show',
            'edit' => 'admin.loyalty.edit',
            'update' => 'admin.loyalty.update',
            'destroy' => 'admin.loyalty.destroy',
        ]);

         Route::resource('users', UserController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);


         Route::resource('roles', RoleController::class)->names([
            'index' => 'admin.roles.index',
            'create' => 'admin.roles.create',
            'store' => 'admin.roles.store',
            'show' => 'admin.roles.show',
            'edit' => 'admin.roles.edit',
            'update' => 'admin.roles.update',
            'destroy' => 'admin.roles.destroy',
        ]);


         Route::post('/users/{user}/resend-verification', [UserController::class, 'resendVerification'])
            ->name('admin.users.resend-verification');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('admin.users.reset-password');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('admin.users.toggle-status');
        Route::get('/users/stats', [UserController::class, 'getStats'])->name('admin.users.stats');

        // Export Routes
        // Users Exports
        Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->name('admin.users.export.pdf');
        Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->name('admin.users.export.excel');
        Route::get('/users/export/csv', [UserController::class, 'exportCsv'])->name('admin.users.export.csv');
        Route::get('/users/print', [UserController::class, 'print'])->name('admin.users.print');

        // Services Exports
        Route::get('/services/export/pdf', [AdminServiceController::class, 'exportPdf'])->name('admin.services.export.pdf');
        Route::get('/services/export/excel', [AdminServiceController::class, 'exportExcel'])->name('admin.services.export.excel');
        Route::get('/services/export/csv', [AdminServiceController::class, 'exportCsv'])->name('admin.services.export.csv');
        Route::get('/services/print', [AdminServiceController::class, 'print'])->name('admin.services.print');

        // Hairstyles Exports
        Route::get('/hairstyles/export/pdf', [AdminHairstyleController::class, 'exportPdf'])->name('admin.hairstyles.export.pdf');
        Route::get('/hairstyles/export/excel', [AdminHairstyleController::class, 'exportExcel'])->name('admin.hairstyles.export.excel');
        Route::get('/hairstyles/export/csv', [AdminHairstyleController::class, 'exportCsv'])->name('admin.hairstyles.export.csv');
        Route::get('/hairstyles/print', [AdminHairstyleController::class, 'print'])->name('admin.hairstyles.print');

        // Bookings Exports
        Route::get('/bookings/export/pdf', [AdminBookingController::class, 'exportPdf'])->name('admin.bookings.export.pdf');
        Route::get('/bookings/export/excel', [AdminBookingController::class, 'exportExcel'])->name('admin.bookings.export.excel');
        Route::get('/bookings/export/csv', [AdminBookingController::class, 'exportCsv'])->name('admin.bookings.export.csv');
        Route::get('/bookings/print', [AdminBookingController::class, 'print'])->name('admin.bookings.print');

        // Transactions Exports
        Route::get('/transactions/export/pdf', [TransactionController::class, 'exportPdf'])->name('admin.transactions.export.pdf');
        Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('admin.transactions.export.excel');
        Route::get('/transactions/export/csv', [TransactionController::class, 'exportCsv'])->name('admin.transactions.export.csv');
        Route::get('/transactions/print', [TransactionController::class, 'print'])->name('admin.transactions.print');

        // Loyalty Exports
        Route::get('/loyalty/export/pdf', [AdminLoyaltyController::class, 'exportPdf'])->name('admin.loyalty.export.pdf');
        Route::get('/loyalty/export/excel', [AdminLoyaltyController::class, 'exportExcel'])->name('admin.loyalty.export.excel');
        Route::get('/loyalty/export/csv', [AdminLoyaltyController::class, 'exportCsv'])->name('admin.loyalty.export.csv');
        Route::get('/loyalty/print', [AdminLoyaltyController::class, 'print'])->name('admin.loyalty.print');
    });

   
    /*
    |--------------------------------------------------------------------------
    | Verification Resend
    |--------------------------------------------------------------------------
    */

});

require __DIR__.'/auth.php';
