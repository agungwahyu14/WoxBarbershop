<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Hairstyle;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\DB; //


class BookingController extends Controller
{
  public function index(Request $request)
{
    if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('pegawai')) {
        return redirect()->action([DashboardController::class, 'index']);
    }

    Log::info('BookingController@index accessed', [
        'is_ajax' => $request->ajax(),
        'user_id' => auth()->id(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);

    if ($request->ajax()) {
        Log::info('Processing AJAX request for bookings datatable');

        try {
            $query = Booking::with(['user', 'service', 'hairstyle']);

            $data = $query->get();

            Log::info('Bookings data retrieved successfully', [
                'total_records' => $data->count()
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', fn($row) => $row->user->name ?? '-')
                ->editColumn('service_id', fn($row) => $row->service->name ?? '-')
                ->editColumn('hairstyle_id', fn($row) => $row->hairstyle->name ?? '-')
                ->editColumn('date_time', fn($row) => \Carbon\Carbon::parse($row->date_time)->format('d M Y H:i'))
                ->editColumn('status', function ($row) {
                    $status = strtolower($row->status);
                    $color = match ($status) {
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'confirmed' => 'bg-green-100 text-green-800',
                        default => 'bg-gray-100 text-gray-800',
                    };

                    return '<span class="px-2 py-1 rounded-full text-xs font-semibold ' . $color . '">' . ucfirst($status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('bookings.edit', $row->id);
                    $deleteUrl = route('bookings.destroy', $row->id);

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
                ->rawColumns(['action', 'status'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Error processing AJAX request for bookings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to load bookings data'
            ], 500);
        }
    }

    Log::info('Returning bookings index view');
    return view('admin.bookings.index');
}


    public function create()
{
    $services = Service::all();
    $hairstyles = Hairstyle::all();
    return view('bookings.create', compact('services', 'hairstyles'));
}



public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'hairstyle_id' => 'required|exists:hairstyles,id',
            'date_time' => 'required|date',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction(); // mulai transaksi database

        $date = date('Y-m-d', strtotime($request->date_time));
        $currentCount = Booking::whereDate('date_time', $date)->count();
        $queueNumber = ($currentCount % 20) + 1;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'service_id' => $request->service_id,
            'hairstyle_id' => $request->hairstyle_id,
            'date_time' => $request->date_time,
            'description' => $request->description,
            'queue_number' => $queueNumber,
            'status' => 'pending',
        ]);

        $service = Service::findOrFail($request->service_id);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'total_amount' => $service->price,
            'payment_method' => null,
            'payment_status' => 'pending',
        ]);

        if (!$transaction) {
            Log::error('Gagal membuat transaksi.', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
            ]);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat transaksi.');
        }

        DB::commit();

        Log::info('Booking & Transaction berhasil dibuat.', [
            'booking_id' => $booking->id,
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat!');
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Gagal membuat booking dan transaksi.', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
            'request' => $request->all()
        ]);

        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memproses booking.');
    }
}


    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

   public function edit($id)
{   
     $users = User::all();
      $booking = Booking::findOrFail($id);
    $services = Service::all();
    $hairstyles = Hairstyle::all();
    return view('admin.bookings.edit', compact('users', 'booking', 'services', 'hairstyles'));
}

public function update(Request $request, $id)
{
    $booking = Booking::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'service_id' => 'required|exists:services,id',
        'hairstyle_id' => 'required|exists:hairstyles,id',
        'date_time' => 'required|date',
        'description' => 'nullable|string|max:1000',
        'status' => 'required|in:pending,confirmed,cancelled,completed',
    ]);

    $booking->update([
        'name' => $request->name,
        'service_id' => $request->service_id,
        'hairstyle_id' => $request->hairstyle_id,
        'date_time' => $request->date_time,
        'description' => $request->description,
        'status' => $request->status,
    ]);

    return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
}


   public function destroy(Booking $booking)
{
    $booking->delete();

    if (request()->ajax()) {
        return response()->json([
            'message' => 'Booking deleted successfully.'
        ]);
    }

    return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
}

}

