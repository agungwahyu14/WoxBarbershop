<?php

namespace App\Http\Controllers\Admin;

// use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Midtrans\Config;
use Illuminate\Support\Facades\Http;
use Midtrans\Transaction as MidtransTransaction;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $query = Transaction::
            all();

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('name', function ($row) {
                return '<div style="width: 150px;">' . e($row->name) . '</div>';
            })

            ->editColumn('email', function ($row) {
                return '<div style="width: 200px;">' . e($row->email) . '</div>';
            })

            ->editColumn('transaction_time', function($row) {
                return \Carbon\Carbon::parse($row->transaction_time)->format('d M Y H:i');
            })

            ->editColumn('gross_amount', function($row) {
                return 'Rp ' . number_format($row->gross_amount, 0, ',', '.');
            })

            ->editColumn('transaction_status', function($row) {
    $badgeColor = match($row->transaction_status) {
        'pending' => 'bg-yellow-100 text-yellow-800',
        'settlement' => 'bg-green-100 text-green-800',
        'cancel' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800'
    };

    $statusLabel = match($row->transaction_status) {
        'pending' => 'Menunggu',
        'settlement' => 'Sukses',
        'cancel' => 'Gagal',
        default => ucfirst($row->transaction_status)
    };

    return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $badgeColor . '">' . $statusLabel . '</span>';
})


            ->addColumn('action', function ($row) {
                return '<div class="flex justify-center space-x-2">
                    <a href="' . route('transactions.show', $row->id) . '" class="btn btn-sm bg-blue-100 text-blue-600 rounded px-2 py-1 hover:bg-blue-200">
                        <i class="mdi mdi-eye"></i>
                    </a>
                </div>';
            })

            ->rawColumns(['name', 'email', 'transaction_status', 'action'])

            ->make(true);
    }

    return view('admin.transactions.index');
}



   public function show(Transaction $transaction)
{
    $user = auth()->user();

    // Admin dan pegawai bisa lihat semua, pelanggan hanya miliknya
    if (!$user->hasAnyRole(['admin', 'pegawai']) && $transaction->user_id !== $user->id) {
        abort(403);
    }

    return view('admin.transactions.show', compact('transaction'));
}

}