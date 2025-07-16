<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Hairstyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class BookingController extends Controller
{
    public function index()
    {
    $bookings = Booking::with(['user', 'service', 'hairstyle'])->latest()->get();
    $user = Auth::user();
    $services = Service::all();
    $hairstyles = Hairstyle::all();

    if ($user->can(['owner', 'pegawai'])) {
        $bookings = Booking::with(['user', 'service', 'hairstyle'])->latest()->get();
    $user = Auth::user();
        return view('admin.bookings.index', compact('bookings', 'services', 'hairstyles', 'users'));
    }

    return view('dashboard', compact('bookings', 'services', 'hairstyles', 'user'));
       
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

            $queueNumber = Booking::whereDate('date_time', date('Y-m-d', strtotime($request->date_time)))
                ->count() + 1;

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'service_id' => $request->service_id,
                'hairstyle_id' => $request->hairstyle_id,
                'date_time' => $request->date_time,
                'notes' => $request->notes,
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

