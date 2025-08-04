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
        // Load relasi booking dan booking->user
        $query = Transaction::all();

        $data = $query;

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('transaction_time', function($row) {
                return \Carbon\Carbon::parse($row->transaction_time)->format('d M Y H:i');
            })
     
            ->addColumn('action', function ($row) {
    return '<div class="flex justify-center space-x-2">
        <a href="' . route('transactions.show', $row->id) . '" class="btn btn-sm bg-blue-100 text-blue-600 rounded px-2 py-1 hover:bg-blue-200">
            <i class="mdi mdi-eye"></i>
        </a>
    </div>';
})

            ->rawColumns([
                'action'
            ])
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