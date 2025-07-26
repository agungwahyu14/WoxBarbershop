<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('TransactionController@index accessed', [
                'user_id' => auth()->id(),
                'role' => auth()->user()->roles->pluck('name'),
                'is_ajax' => $request->ajax(),
            ]);

            $user = auth()->user();

            // AJAX untuk admin dan pegawai
            if ($request->ajax() && $user->hasAnyRole(['admin', 'pegawai'])) {
                $query = Transaction::with(['user', 'booking.service', 'service']);

                return DataTables::of($query->get())
                    ->addIndexColumn()
                    ->addColumn('customer_info', function($row) {
                        if ($row->user) {
                            return '<div class="flex items-center space-x-3">
                               
                                <div>
                                    <div class="font-medium text-gray-900">' . $row->user->name . '</div>
                                    <div class="text-sm text-gray-500">' . $row->user->email . '</div>
                                </div>
                            </div>';
                        }
                        return '<span class="text-gray-400">No customer</span>';
                    })
                    ->addColumn('booking_info', function($row) {
                        if ($row->booking) {
                            $date = \Carbon\Carbon::parse($row->booking->date_time);
                            return '<div>
                                <div class="font-medium text-gray-900">Booking #' . $row->booking->id . '</div>
                                <div class="text-sm text-gray-500">' . $date->format('M d, Y H:i') . '</div>
                                <div class="text-xs text-gray-400">Queue: ' . ($row->booking->queue_number ?? '-') . '</div>
                            </div>';
                        }
                        return '<span class="text-gray-400">No booking</span>';
                    })
                    ->addColumn('service_info', function($row) {
                        $service = $row->service ?? $row->booking->service ?? null;
                        if ($service) {
                            return '<div>
                                <div class="font-medium text-gray-900">' . $service->name . '</div>
                                <div class="text-sm text-gray-500">Rp ' . number_format($service->price, 0, ',', '.') . '</div>
                                <div class="text-xs text-gray-400">' . ($service->duration ?? 30) . ' minutes</div>
                            </div>';
                        }
                        return '<span class="text-gray-400">No service</span>';
                    })
                    ->addColumn('amount_formatted', function($row) {
                        return '<div class="text-right">
                            <div class="font-bold text-lg text-green-600">Rp ' . number_format($row->total_amount, 0, ',', '.') . '</div>
                        </div>';
                    })
                    ->addColumn('payment_method_display', function($row) {
                        $method = ucfirst(str_replace('_', ' ', $row->payment_method));
                        $colors = [
                            'Cash' => 'bg-green-100 text-green-800 border-green-200',
                            'Bank transfer' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'E wallet' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'Credit card' => 'bg-orange-100 text-orange-800 border-orange-200',
                        ];
                        $icons = [
                            'Cash' => 'fas fa-money-bill',
                            'Bank transfer' => 'fas fa-university',
                            'E wallet' => 'fas fa-wallet',
                            'Credit card' => 'fas fa-credit-card',
                        ];
                        $color = $colors[$method] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        $icon = $icons[$method] ?? 'fas fa-question-circle';
                        
                        return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ' . $color . '">
                            <i class="' . $icon . ' mr-1"></i>
                            ' . $method . '
                        </span>';
                    })
                    ->addColumn('status_badge', function($row) {
                        $status = strtolower($row->payment_status);
                        $colors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                            'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                            'paid' => 'bg-green-100 text-green-800 border-green-200',
                            'failed' => 'bg-red-100 text-red-800 border-red-200',
                            'settlement' => 'bg-green-100 text-green-800 border-green-200',
                            'expire' => 'bg-gray-100 text-gray-800 border-gray-200',
                        ];
                        $icons = [
                            'pending' => 'fas fa-clock',
                            'cancelled' => 'fas fa-times-circle',
                            'confirmed' => 'fas fa-check',
                            'paid' => 'fas fa-check-circle',
                            'failed' => 'fas fa-exclamation-triangle',
                            'settlement' => 'fas fa-check-double',
                            'expire' => 'fas fa-history',
                        ];
                        $color = $colors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        $icon = $icons[$status] ?? 'fas fa-question-circle';
                        
                        return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ' . $color . '">
                            <i class="' . $icon . ' mr-1"></i>
                            ' . ucfirst($status) . '
                        </span>';
                    })
                    ->addColumn('transaction_date', function($row) {
                        $date = \Carbon\Carbon::parse($row->created_at);
                        return '<div class="text-center">
                            <div class="font-medium text-gray-900">' . $date->format('M d, Y') . '</div>
                            <div class="text-sm text-gray-500">' . $date->format('H:i A') . '</div>
                            <div class="text-xs text-gray-400">' . $date->diffForHumans() . '</div>
                        </div>';
                    })
                    ->addColumn('actions', function($row) {
                        $actions = '<div class="flex justify-center items-center space-x-2">';
                        
                        // View details button
                        $actions .= '<button onclick="openTransactionDetail(' . $row->id . ')" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200" 
                                           title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>';
                        
                        // Edit button for admin
                        if (auth()->user()->hasRole('admin')) {
                            $editUrl = route('transactions.edit', $row->id);
                            $actions .= '<a href="' . $editUrl . '" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-colors duration-200" 
                                               title="Edit Transaction">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>';
                        }
                        
                        // Status-specific actions
                        switch(strtolower($row->payment_status)) {
                            case 'pending':
                                $actions .= '<button onclick="markAsSettled(' . $row->id . ')" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 hover:bg-purple-200 text-purple-600 transition-colors duration-200" 
                                                   title="Mark as Settled">
                                                <i class="fas fa-check-double text-sm"></i>
                                            </button>';
                                break;
                            case 'paid':
                            case 'settlement':
                                $actions .= '<button onclick="processRefund(' . $row->id . ')" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-600 transition-colors duration-200" 
                                                   title="Process Refund">
                                                <i class="fas fa-undo text-sm"></i>
                                            </button>';
                                break;
                        }
                        
                        // Delete button (admin only)
                        if (auth()->user()->hasRole('admin')) {
                            $actions .= '<button onclick="deleteTransaction(' . $row->id . ')" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200" 
                                               title="Delete Transaction">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>';
                        }
                        
                        $actions .= '</div>';
                        return $actions;
                    })
                    ->rawColumns(['customer_info', 'booking_info', 'service_info', 'amount_formatted', 'payment_method_display', 'status_badge', 'transaction_date', 'actions'])
                    ->make(true);
            }

            // Pelanggan: tampilkan transaksi sendiri
            if ($user->hasRole('pelanggan')) {
                $transactions = Transaction::where('user_id', auth()->id())
                    ->with(['booking.service', 'service'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                return view('transactions.index', compact('transactions'));
            }

            // View untuk admin dan pegawai
            if ($user->hasAnyRole(['admin', 'pegawai'])) {
                return view('admin.transactions.index');
            }

            // Jika role tidak dikenali
            abort(403, 'Unauthorized');

        } catch (\Exception $e) {
            Log::error('Error in TransactionController@index', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat memuat data transaksi.'], 500);
            }

            return response()->view('errors.500', [], 500);
        }
    }

    public function create()
    {
        if (!auth()->user()->hasRole('pelanggan')) {
            abort(403, 'Hanya pelanggan yang dapat membuat transaksi.');
        }

        $bookings = Booking::where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->with('service')
            ->get();

        $services = Service::where('status', 'active')->get();

        return view('transactions.create', compact('bookings', 'services'));
    }

    public function store(Request $request)
    {
        try {
            if (!auth()->user()->hasRole('pelanggan')) {
                abort(403, 'Hanya pelanggan yang dapat membuat transaksi.');
            }

            $request->validate([
                'booking_id' => 'nullable|exists:bookings,id',
                'service_id' => 'nullable|exists:services,id',
                'total_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,bank_transfer,e_wallet,credit_card',
                'payment_status' => 'nullable|string'
            ]);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'booking_id' => $request->booking_id,
                'service_id' => $request->service_id,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status ?? 'pending',
                'price' => $request->total_amount, // For compatibility
            ]);

            Log::info('Transaction created.', ['id' => $transaction->id]);

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat.');

        } catch (\Exception $e) {
            Log::error('Gagal membuat transaksi.', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat transaksi.');
        }
    }

    public function show(Transaction $transaction)
    {
        $user = auth()->user();
        
        // Admin dan pegawai bisa lihat semua, pelanggan hanya miliknya
        if (!$user->hasAnyRole(['admin', 'pegawai']) && $transaction->user_id !== $user->id) {
            abort(403);
        }

        $transaction->load(['user', 'booking.service', 'service']);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'pegawai'])) {
            abort(403);
        }

        $transaction->load(['user', 'booking.service', 'service']);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'pegawai'])) {
            abort(403);
        }

        $request->validate([
            'payment_status' => 'required|string|in:pending,paid,cancelled,failed,confirmed,settlement,expire',
            'payment_method' => 'nullable|in:cash,bank_transfer,e_wallet,credit_card',
            'total_amount' => 'nullable|numeric|min:0'
        ]);

        $updateData = [
            'payment_status' => $request->payment_status
        ];

        if ($request->filled('payment_method')) {
            $updateData['payment_method'] = $request->payment_method;
        }

        if ($request->filled('total_amount')) {
            $updateData['total_amount'] = $request->total_amount;
            $updateData['price'] = $request->total_amount; // For compatibility
        }

        $transaction->update($updateData);

        Log::info('Transaction updated', [
            'transaction_id' => $transaction->id,
            'updated_by' => auth()->id(),
            'changes' => $updateData
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'pegawai'])) {
            abort(403);
        }

        try {
            $transactionId = $transaction->id;
            $transaction->delete();

            Log::info('Transaction deleted', [
                'transaction_id' => $transactionId,
                'deleted_by' => auth()->id()
            ]);

            return response()->json(['message' => 'Transaksi berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Failed to delete transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Gagal menghapus transaksi.'], 500);
        }
    }
}