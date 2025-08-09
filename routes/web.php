<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HairstyleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MidtransCallbackController;

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
     Route::resource('rekomendasi', RecommendationController::class);
    
    Route::get('/transaction', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/transaction/{orderId}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/transaction/va/{orderId}', [PaymentController::class, 'showVA']);
    Route::get('/transaction/download/{orderId}', [PaymentController::class, 'downloadReceipt'])->name('transaction.download');
    Route::get('/payment/{id}', [PaymentController::class, 'pay'])->name('payment.pay');






    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    Route::resource('bookings', BookingController::class);
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
   
});

Route::prefix('admin')->middleware(['auth', 'role:admin|pegawai'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('hairstyles', HairstyleController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('loyalties', LoyaltyController::class);


    // Enhanced User Management Routes
    Route::post('/users/{user}/resend-verification', [UserController::class, 'resendVerification'])->name('admin.users.resend-verification');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::get('/users/stats', [UserController::class, 'getStats'])->name('admin.users.stats');



    // Tambahkan route admin lainnya di sini
});

// Verification Routes (outside admin group)
Route::post('/verification/resend', function(Request $request) {
    $user = \App\Models\User::findOrFail($request->user_id);
    if ($user->hasVerifiedEmail()) {
        return response()->json(['success' => false, 'message' => 'Email already verified']);
    }
    $user->sendEmailVerificationNotification();
    return response()->json(['success' => true, 'message' => 'Verification email sent']);
})->name('verification.resend')->middleware('auth');

require __DIR__ . '/auth.php';

// Midtrans notification callback (no auth required)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
