<?php

use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\HairstyleController as AdminHairstyleController;
use App\Http\Controllers\Admin\HairstyleScoreController;    
use App\Http\Controllers\Admin\HairstyleRekomendasiController;
use App\Http\Controllers\Admin\LoyaltyController as AdminLoyaltyController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FeedbackController as CustomerFeedbackService;
use App\Http\Controllers\DashboardController;
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

Route::middleware(['auth', 'verified'])->group(function () {

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

        // Payment & Transactions
        Route::get('/transaction', [PaymentController::class, 'index'])->name('payment.index');
        Route::get('/transactions', [PaymentController::class, 'index'])->name('transactions.index');
        Route::post('/transaction/cash', [PaymentController::class, 'cashPayment'])->name('payment.cash');
        Route::get('/transaction/{orderId}', [PaymentController::class, 'show'])->name('payment.show');
        Route::get('/transaction/va/{orderId}', [PaymentController::class, 'showVA']);
        Route::get('/transaction/download/{orderId}', [PaymentController::class, 'downloadReceipt'])->name('transaction.download');
        Route::get('/payment/{id}', [PaymentController::class, 'pay'])->name('payment.pay');

        // Recommendation
        Route::resource('rekomendasi', RecommendationController::class);

        // Customer Feedback
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/create/{booking}', [CustomerFeedbackService::class, 'create'])->name('create');
            Route::post('/store', [CustomerFeedbackService::class, 'store'])->name('store');
            Route::get('/{feedback}', [CustomerFeedbackService::class, 'show'])->name('show');
            Route::get('/{feedback}/edit', [CustomerFeedbackService::class, 'edit'])->name('edit');
            Route::put('/{feedback}', [CustomerFeedbackService::class, 'update'])->name('update');
        });
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
        Route::get('/bookings/statistics', [BookingController::class, 'getStatistics'])
            ->name('admin.bookings.statistics');

               Route::get('bookings/export/csv', [BookingController::class, 'exportCsv'])->name('admin.bookings.export.csv');
    Route::get('bookings/export/pdf', [BookingController::class, 'exportPdf'])->name('admin.bookings.export.pdf');

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
            'score' => 'admin.hairstyles.score.index',
            'rekomendasi' => 'admin.hairstyles.rekomendasi.index',

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

    // CRUD Hairstyle Score
    Route::resource('hairstyle-scores', HairstyleScoreController::class)->names([
        'index' => 'admin.hairstyles.score.index',
        'create' => 'admin.hairstyles.score.create',
        'store' => 'admin.hairstyles.score.store',
        'edit' => 'admin.hairstyles.score.edit',
        'update' => 'admin.hairstyles.score.update',
        'destroy' => 'admin.hairstyles.score.destroy',
    ]);

    // Rekomendasi (AHP Test)
  
        Route::resource('transactions', TransactionController::class)->names([
            'index' => 'admin.transactions.index',
            'create' => 'admin.transactions.create',
            'store' => 'admin.transactions.store',
            'show' => 'admin.transactions.show',
            'edit' => 'admin.transactions.edit',
            'update' => 'admin.transactions.update',
            'destroy' => 'admin.transactions.destroy',
        ]);


               Route::get('transactions/export/csv', [TransactionController::class, 'exportCsv'])->name('admin.transactions.export.csv');
    Route::get('transactions/export/pdf', [TransactionController::class, 'exportPdf'])->name('admin.transactions.export.pdf');

        // Additional transaction routes
        Route::post('transactions/{id}/settlement', [TransactionController::class, 'markAsSettlement'])->name('admin.transactions.settlement');
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
         Route::get('users/export/csv', [UserController::class, 'exportCsv'])->name('admin.users.export.csv');
    Route::get('users/export/pdf', [UserController::class, 'exportPdf'])->name('admin.users.export.pdf');


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

        // Feedback Management
        Route::resource('feedbacks', FeedbackController::class)->names([
            'index' => 'admin.feedbacks.index',
            'show' => 'admin.feedbacks.show',
            'update' => 'admin.feedbacks.update',
            'destroy' => 'admin.feedbacks.destroy',
        ])->only(['index', 'show', 'update', 'destroy']);
        Route::post('/feedbacks/{feedback}/toggle-public', [FeedbackController::class, 'togglePublic'])
            ->name('admin.feedbacks.toggle-public');

        // Product Management
        Route::resource('products', AdminProductController::class)->names([
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'show' => 'admin.products.show',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]);
        Route::post('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])
            ->name('admin.products.toggle-status');

        // Reports Management
        Route::prefix('reports')->name('admin.reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
            Route::get('/bookings', [ReportController::class, 'bookings'])->name('bookings');
            Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
            Route::post('/export-financial', [ReportController::class, 'exportFinancial'])->name('export-financial');
        });

        // System Management
        Route::prefix('system')->name('admin.system.')->group(function () {
            Route::get('/', [SystemController::class, 'index'])->name('index');
            Route::post('/settings', [SystemController::class, 'updateSettings'])->name('settings');
            Route::post('/backup', [SystemController::class, 'backup'])->name('backup');
            Route::post('/clear-cache', [SystemController::class, 'clear-cache'])->name('clear-cache');
        });

    });

   
    /*
    |--------------------------------------------------------------------------
    | Verification Resend
    |--------------------------------------------------------------------------
    */

});

require __DIR__.'/auth.php';

// Test route for forgot password - Disabled for now, may be used in the future
// Route::get('/test-forgot-password', function() { return view('test-forgot-password'); });
