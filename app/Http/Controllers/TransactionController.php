<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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

        // AJAX hanya untuk admin dan pegawai
        if ($request->ajax() && $user->hasAnyRole(['admin', 'pegawai'])) {
            $query = Transaction::with(['user', 'booking', 'service']);

            return DataTables::of($query->get())
                ->addIndexColumn()
                 ->editColumn('user_id', fn($row) => $row->user->name ?? '-')  
                 ->editColumn('booking_id', fn($row) => $row->booking->name ?? '-')
                  ->editColumn('service_id', fn($row) => $row->service->name ?? '-')
                  ->editColumn('payment_status', function ($row) {
    $status = strtolower($row->payment_status);
    $color = match ($status) {
        'pending' => 'bg-yellow-100 text-yellow-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'confirmed' => 'bg-green-100 text-green-800',
        default => 'bg-gray-100 text-gray-800',
    };

    return '<span class="px-2 py-1 rounded-full text-xs font-semibold ' . $color . '">' . ucfirst($status) . '</span>';
})

                ->editColumn('created_at', fn($row) => \Carbon\Carbon::parse($row->created_at)->locale('id')->translatedFormat('d F Y H:i'))
                ->addColumn('action', function ($row) {
                    $editUrl = route('transactions.edit', $row->id);
                    $deleteUrl = route('transactions.destroy', $row->id);

                    return '
                            <div class="flex justify-center items-center gap-2">
                                <a href="' . $editUrl . '" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition deleteBtn" data-id="' . $row->id . '" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        ';
                })
                ->rawColumns(['action', 'payment_status'])
                ->make(true);
        }

        // Pelanggan: tampilkan transaksi sendiri (tanpa AJAX/DataTables)
        if ($user->hasRole('pelanggan')) {
            $transactions = Transaction::where('user_id', auth()->id())
                ->where('payment_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();


            return view('transactions.index', compact('transactions'));
        }

        // View untuk admin dan pegawai (pakai AJAX/DataTables)
        if ($user->hasAnyRole(['admin', 'pegawai'])) {
            return view('admin.transactions.index');
        }

        // Jika role tidak dikenali
        abort(403, 'Unauthorized');

    } catch (\Exception $e) {
        Log::error('Error in TransactionController@index', [
            'message' => $e->getMessage()
        ]);

        return response()->view('errors.500', [], 500);
    }
}


    public function store(Request $request)
    {
        try {
            if (!auth()->user()->hasRole('pelanggan')) {
                abort(403, 'Hanya pelanggan yang dapat membuat transaksi.');
            }

            $request->validate([
                'total' => 'required|numeric',
                'status' => 'nullable|string'
            ]);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total' => $request->total,
                'status' => $request->status ?? 'pending'
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

    public function edit(Transaction $transaction)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'pegawai'])) {
            abort(403);
        }

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'pegawai'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,paid,cancelled,failed'
        ]);

        $transaction->update([
            'status' => $request->status
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'pegawai'])) {
            abort(403);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaksi berhasil dihapus.']);
    }
}
