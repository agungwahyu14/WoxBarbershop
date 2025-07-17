<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Hairstyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;


class BookingController extends Controller
{
  public function index(Request $request)
{
    try {
        // Redirect jika bukan admin atau pegawai
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('pegawai')) {
            return redirect()->action([DashboardController::class, 'index']);
        }

        // Log akses
        Log::info('BookingController@index accessed', [
            'is_ajax' => $request->ajax(),
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->ajax()) {
            Log::info('Processing AJAX request for bookings datatable');

            try {
                $data = Booking::with(['user', 'service', 'hairstyle'])
                    ->latest()
                    ->get();

                Log::info('Bookings data retrieved successfully', [
                    'total_records' => $data->count()
                ]);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('user_id', fn($row) => $row->user->name ?? '-')
                    ->editColumn('service_id', fn($row) => $row->service->name ?? '-')
                    ->editColumn('hairstyle_id', fn($row) => $row->hairstyle->name ?? '-')
                    ->editColumn('date_time', fn($row) => \Carbon\Carbon::parse($row->date_time)->format('d M Y H:i'))
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
                    ->rawColumns(['action'])
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

    } catch (\Exception $e) {
        Log::error('Error in BookingController@index', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->view('errors.500', [], 500);
    }
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

        $date = date('Y-m-d', strtotime($request->date_time));

        // Hitung jumlah booking di tanggal tersebut
        $currentCount = Booking::whereDate('date_time', $date)->count();

        // Jika sudah mencapai 20, reset ke 1, jika belum tambahkan 1
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

        Log::info('Booking berhasil dibuat.', [
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'date_time' => $request->date_time,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat!');
    } catch (\Exception $e) {
        Log::error('Gagal membuat booking.', [
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

   public function edit(Booking $booking)
{
    $services = Service::all();
    $hairstyles = Hairstyle::all();
    return view('bookings.edit', compact('booking', 'services', 'hairstyles'));
}

public function update(Request $request, Booking $booking)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'service_id' => 'required|exists:services,id',
        'hairstyle_id' => 'required|exists:hairstyles,id',
        'date_time' => 'required|date',
        'notes' => 'nullable|string|max:1000',
        'status' => 'required|in:pending,confirmed,cancelled,completed',
    ]);

    $booking->update([
        'name' => $request->name,
        'service_id' => $request->service_id,
        'hairstyle_id' => $request->hairstyle_id,
        'date_time' => $request->date_time,
        'notes' => $request->notes,
        'status' => $request->status,
    ]);

    return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
}


    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}

