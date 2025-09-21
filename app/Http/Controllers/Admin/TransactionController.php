<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Booking;
use App\Traits\ExportTrait;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    use ExportTrait;
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


            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('name', function ($row) {
                    return '<div style="width: 150px;">'.e($row->name).'</div>';
                })

                ->editColumn('email', function ($row) {
                    return '<div style="width: 200px;">'.e($row->email).'</div>';
                })

                ->editColumn('transaction_time', function ($row) {
                    return \Carbon\Carbon::parse($row->transaction_time)->format('d M Y H:i');
                })

                ->editColumn('gross_amount', function ($row) {
                    return 'Rp '.number_format($row->gross_amount, 0, ',', '.');
                })

                ->editColumn('transaction_status', function ($row) {
                    $badgeColor = match ($row->transaction_status) {
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'settlement' => 'bg-green-100 text-green-800',
                        'cancel' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };

                    $statusLabel = match ($row->transaction_status) {
                        'pending' => 'Menunggu',
                        'settlement' => 'Sukses',
                        'cancel' => 'Gagal',
                        default => ucfirst($row->transaction_status)
                    };

                    return '<span class="px-2 py-1 rounded-full text-xs font-medium '.$badgeColor.'">'.$statusLabel.'</span>';
                })

                ->addColumn('action', function ($row) {
    $actions = '<div class="flex justify-center space-x-2">
        <a href="'.route('admin.transactions.show', $row->id).'" 
            class="btn btn-sm bg-blue-100 text-blue-600 rounded px-2 py-1 hover:bg-blue-200" 
            title="Lihat Detail">
            <i class="mdi mdi-eye"></i>
        </a>';
    
    // Show settlement button only if status is not already settlement
    if ($row->transaction_status !== 'settlement') {
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


                ->rawColumns(['name', 'email', 'transaction_status', 'action'])

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
        ->with('success', 'Transaksi berhasil diperbarui.');
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
    public function exportPdf(Request $request)
    {
        $query = Transaction::with(['booking.user', 'booking.service'])->orderBy('created_at', 'desc');
        return $this->exportPDF($request, $query, 'admin.exports.transactions_pdf');
    }

    public function exportExcel(Request $request)
    {
        return $this->exportExcel($request, TransactionsExport::class);
    }

    public function exportCsv(Request $request)
    {
        return $this->exportCSV($request, TransactionsExport::class);
    }

    public function print(Request $request)
    {
        $query = Transaction::with(['booking.user', 'booking.service'])->orderBy('created_at', 'desc');
        return $this->printView($request, $query, 'admin.exports.transactions_print');
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
        $transaction = Transaction::findOrFail($id);
        
        // Update transaction status to settlement
        $transaction->update([
            'transaction_status' => 'settlement'
        ]);

        // Update related booking if exists
        $booking = Booking::where('id', $transaction->order_id)->first();
        if ($booking) {
            $booking->update([
                'status' => 'completed',
                'payment_status' => 'paid'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dikonfirmasi sebagai settlement.'
        ]);
    }
}
