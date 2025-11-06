<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Booking;
use App\Traits\ExportTrait;
use App\Exports\TransactionsExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $query = Transaction::query();

        // Apply month filter
        if ($request->has('month_filter') && ! empty($request->month_filter)) {
            $query->whereMonth('transaction_time', $request->month_filter);
        }

        // Apply year filter
        if ($request->has('year_filter') && ! empty($request->year_filter)) {
            $query->whereYear('transaction_time', $request->year_filter);
        }

        // ✅ Apply status filter
        if ($request->has('status_filter') && ! empty($request->status_filter)) {
            $query->where('transaction_status', $request->status_filter);
        }

        // ✅ Apply type filter (assuming transaction_type exists in the database)
        if ($request->has('type_filter') && ! empty($request->type_filter)) {
            $query->where('transaction_type', $request->type_filter);
        }

        $data = $query->orderByDesc('id')->get();

        return DataTables::of($data)
            ->addIndexColumn()

            // Format name
            ->editColumn('name', function ($row) {
                return '<div style="width: 150px;">'.e($row->name).'</div>';
            })

            // Format email
            ->editColumn('email', function ($row) {
                return '<div style="width: 200px;">'.e($row->email).'</div>';
            })

            // Format transaction time
            ->editColumn('transaction_time', function ($row) {
                return \Carbon\Carbon::parse($row->transaction_time)->format('d M Y H:i');
            })

            // Format gross amount (amount)
            ->editColumn('gross_amount', function ($row) {
                return 'Rp '.number_format($row->gross_amount, 0, ',', '.');
            })

            // Format transaction status
            ->editColumn('transaction_status', function ($row) {
                $badgeColor = match ($row->transaction_status) {
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'settlement' => 'bg-green-100 text-green-800',
                    'cancel' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-100 text-gray-800'
                };

                $statusLabel = match ($row->transaction_status) {
                    'pending' => __('admin.pending'),
                    'settlement' => __('admin.complete'),
                    'cancel' => __('admin.cancelled'),
                    default => ucfirst($row->transaction_status)
                };

                return '<span class="px-2 py-1 rounded-full text-xs font-medium '.$badgeColor.'">'.$statusLabel.'</span>';
            })

            // Format payment type (bank transfer, etc.)
           ->editColumn('payment_type', function ($row) {
    // Format type as per the payment type
    switch ($row->payment_type) {
        case 'bank_transfer':
            return 'Bank Transfer';
        case 'cash':
            return 'Cash';
        default:
            return '-';
    }
})

            // Actions column
            ->addColumn('action', function ($row) {
                $actions = '<div class="flex justify-center space-x-2">
                    <a href="'.route('admin.transactions.show', $row->id).'" 
                        class="btn btn-sm bg-green-100 text-green-600 rounded px-2 py-1 hover:bg-green-200" 
                        title="Lihat Detail">
                        <i class="mdi mdi-eye"></i>
                    </a>';

                // Show settlement button only if status is not already settlement
                if ($row->transaction_status !== 'settlement' && $row->transaction_status !== 'cancel') {
                    $actions .= '<button type="button" 
                        class="btn btn-sm bg-green-100 text-green-600 rounded px-2 py-1 hover:bg-green-200" 
                        title="Konfirmasi Settlement" 
                        onclick="confirmSettlement('.$row->id.')">
                        <i class="mdi mdi-check-circle"></i>
                    </button>';
                }

                $actions .= '</div>';

                return $actions;
            })

            ->rawColumns(['name', 'email', 'transaction_status', 'transaction_type', 'action']) // Allow raw HTML rendering

            ->make(true);
    }

    return view('admin.transactions.index');
}

    

public function edit(Transaction $transaction)
{
    $user = auth()->user();

    // Admin dan pegawai bisa edit semua, pelanggan hanya miliknya
    if (! $user->hasAnyRole(['admin', 'pegawai']) && $transaction->user_id !== $user->id) {
        abort(403);
    }

    return view('admin.transactions.edit', compact('transaction'));
}

public function update(Request $request, Transaction $transaction)
{
    $request->validate([
        'transaction_status' => 'required|in:pending,settlement,cancel,failed',
        'payment_type' => 'required|in:cash,bank',
    ]);

    $transaction->update([
        'transaction_status' => $request->transaction_status,
        'payment_type' => $request->payment_type,
    ]);

    return redirect()->route('admin.transactions.index')
        ->with('success', __('admin.transaction_updated_successfully'));
}


    

    public function show(Transaction $transaction)
    {
        $user = auth()->user();

        // Admin dan pegawai bisa lihat semua, pelanggan hanya miliknya
        if (! $user->hasAnyRole(['admin', 'pegawai']) && $transaction->user_id !== $user->id) {
            abort(403);
        }

        return view('admin.transactions.show', compact('transaction'));
    }

    // Export methods
   public function exportCsv(Request $request): StreamedResponse
{
    $month = $request->get('month');
    $year = $request->get('year');
    $status = $request->get('status');
    
    // Build filename with filters
    $periodParts = [];
    if ($month && $year) {
        $periodParts[] = Carbon::create($year, $month)->format('M_Y');
    } elseif ($year) {
        $periodParts[] = $year;
    } elseif ($month) {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $periodParts[] = $monthNames[$month - 1];
    }
    
    if ($status) {
        $periodParts[] = ucfirst($status);
    }
    
    $period = !empty($periodParts) ? '_' . implode('_', $periodParts) : '';
    $fileName = 'transactions' . $period . '_' . now()->format('Ymd_His') . '.csv';

    // Query with filters
    $query = Transaction::query();
    
    // Date filters
    if ($month && $year) {
        $query->whereYear('created_at', $year)
              ->whereMonth('created_at', $month);
    } elseif ($year) {
        $query->whereYear('created_at', $year);
    } elseif ($month) {
        $query->whereMonth('created_at', $month);
    }
    
    // Status filter
    if ($status) {
        $query->where('transaction_status', $status);
    }
    
    $transactions = $query->orderBy('created_at', 'desc')->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $callback = function () use ($transactions) {
        $handle = fopen('php://output', 'w');

        // Header CSV
        fputcsv($handle, ['No', 'Order ID', 'Customer Name', 'Customer Email', 'Payment Type', 'Gross Amount', 'Status', 'Created At']);

        // Data rows
        foreach ($transactions as $i => $transaction) {
            fputcsv($handle, [
                $i + 1, // Nomor urut
                $transaction->order_id, // Order ID
                $transaction->name ?? '-', // Customer Name
                $transaction->email ?? '-', // Customer Email
                $this->getPaymentType($transaction->payment_type), // Payment Type (Bank Transfer / Cash)
                'Rp ' . number_format($transaction->gross_amount, 0, ',', '.'), // Gross Amount
                ucfirst($transaction->transaction_status), // Status
                $transaction->created_at->format('d/m/Y H:i'), // Created At
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Get formatted payment type
 */
protected function getPaymentType($paymentType)
{
    switch ($paymentType) {
        case 'bank_transfer':
            return 'Bank Transfer';
        case 'cash':
            return 'Cash';
        default:
            return '-'; // Handle other cases if needed
    }
}



    /**
     * Export transactions to PDF with filter
     */
    public function exportPdf(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        $status = $request->get('status');
        
        // Build filename with filters
        $periodParts = [];
        if ($month && $year) {
            $periodParts[] = Carbon::create($year, $month)->format('M_Y');
        } elseif ($year) {
            $periodParts[] = $year;
        } elseif ($month) {
            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $periodParts[] = $monthNames[$month - 1];
        }
        
        if ($status) {
            $periodParts[] = ucfirst($status);
        }
        
        $period = !empty($periodParts) ? '_' . implode('_', $periodParts) : '';

        // Query with filters
        $query = Transaction::query();
        
        // Date filters
        if ($month && $year) {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        } elseif ($year) {
            $query->whereYear('created_at', $year);
        } elseif ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        // Status filter
        if ($status) {
            $query->where('transaction_status', $status);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.transactions.export_pdf', compact('transactions', 'month', 'year'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('transactions' . $period . '_' . now()->format('Ymd_His') . '.pdf');
    }

    protected function getModelName(): string
    {
        return 'transactions';
    }

    protected function getExportTitle(): string
    {
        return 'Data Transaksi';
    }

    public function markAsSettlement($id)
    {
        DB::beginTransaction();
        
        try {
            $transaction = Transaction::findOrFail($id);
            
            // Update transaction status to settlement
            $transaction->update([
                'transaction_status' => 'settlement'
            ]);

            // Update related booking if exists
            $booking = Booking::where('id', $transaction->order_id)->first();
            if ($booking && $booking->status !== 'completed') {
                $booking->update([
                    'status' => 'completed',
                    'payment_status' => 'paid'
                ]);

                // Add loyalty points when transaction is settled and booking is completed
                // Always add loyalty points for completed paid bookings
                {
                    $user = $booking->user;
                    $loyalty = $user->loyalty;

                    if (!$loyalty) {
                        Log::info('Creating new loyalty record for user on settlement', ['user_id' => $user->id]);
                        
                        $loyalty = \App\Models\Loyalty::create([
                            'user_id' => $user->id,
                            'points' => 0
                        ]);
                    }

                    // Add 1 point for completed haircut
                    $oldPoints = $loyalty->points;
                    $loyalty->addPoints(1);

                    Log::info('Loyalty points added on transaction settlement', [
                        'user_id' => $user->id,
                        'booking_id' => $booking->id,
                        'transaction_id' => $transaction->id,
                        'old_points' => $oldPoints,
                        'new_points' => $loyalty->points,
                        'can_redeem' => $loyalty->canRedeemFreeService()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('admin.transaction_settlement_confirmed_successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to mark transaction as settlement', [
                'transaction_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin.failed_to_confirm_settlement') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a transaction
     */
    public function cancelTransaction($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            // Check if transaction can be cancelled (only pending transactions)
            if (!in_array($transaction->transaction_status, ['pending'])) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.transaction_cannot_be_cancelled')
                ], 400);
            }

            DB::beginTransaction();

            // Update transaction status
            $transaction->update([
                'transaction_status' => 'cancel'
            ]);

            // If there's a related booking, update its status as well
            if ($transaction->order_id) {
                $booking = Booking::find($transaction->order_id);
                if ($booking && $booking->status !== 'cancelled') {
                    $booking->update(['status' => 'cancelled']);
                }
            }

            DB::commit();

            Log::info('Transaction cancelled successfully', [
                'transaction_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => __('admin.transaction_cancelled_successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to cancel transaction', [
                'transaction_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin.failed_to_cancel_transaction') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh transaction status from Midtrans
     */
    public function refreshStatus($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            // Import Midtrans
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Get status from Midtrans
            $status = \Midtrans\Transaction::status($transaction->order_id);

            DB::beginTransaction();

            // Update transaction with latest status from Midtrans
            $transaction->update([
                'transaction_status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? $transaction->payment_type,
                'transaction_time' => isset($status->transaction_time) ? 
                    \Carbon\Carbon::parse($status->transaction_time) : $transaction->transaction_time,
                'fraud_status' => $status->fraud_status ?? $transaction->fraud_status,
            ]);

            // Update related booking status if needed
            if ($transaction->order_id) {
                $booking = Booking::find($transaction->order_id);
                if ($booking) {
                    if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                        if ($booking->status === 'pending') {
                            $booking->update(['status' => 'confirmed']);
                        }
                    } elseif (in_array($status->transaction_status, ['cancel', 'failure', 'expire', 'deny'])) {
                        if ($booking->status !== 'cancelled') {
                            $booking->update(['status' => 'cancelled']);
                        }
                    }
                }
            }

            DB::commit();

            Log::info('Transaction status refreshed successfully', [
                'transaction_id' => $id,
                'old_status' => $transaction->getOriginal('transaction_status'),
                'new_status' => $status->transaction_status,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => __('admin.transaction_status_refreshed_successfully'),
                'transaction' => $transaction->fresh()
            ]);

        } catch (\Midtrans\Exception $e) {
            DB::rollBack();
            
            Log::error('Midtrans API error when refreshing transaction status', [
                'transaction_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin.midtrans_error') . ': ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to refresh transaction status', [
                'transaction_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin.failed_to_refresh_status') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
