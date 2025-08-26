<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Traits\ExportTrait;
use App\Exports\BookingsExport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BookingController extends Controller
{
    use ExportTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Booking::with(['user', 'service'])->orderBy('created_at', 'desc');

            // Apply month filter
            if ($request->has('month_filter') && ! empty($request->month_filter)) {
                $query->whereMonth('date_time', $request->month_filter);
            }

            // Apply year filter
            if ($request->has('year_filter') && ! empty($request->year_filter)) {
                $query->whereYear('date_time', $request->year_filter);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('customer', function ($booking) {
                    return $booking->user->name ?? '-';
                })
                ->addColumn('service', function ($booking) {
                    return $booking->service->name ?? '-';
                })
                ->addColumn('date_time', function ($booking) {
                    return $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('d/m/Y H:i') : '-';
                })
                ->addColumn('status', function ($booking) {
                    $badgeClass = match($booking->status) {
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                    return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $badgeClass . '">' . ucfirst($booking->status) . '</span>';
                })
                ->addColumn('total_price', function ($booking) {
                    return 'Rp' . number_format($booking->total_price, 0, ',', '.');
                })
                ->addColumn('action', function ($booking) {
                    $actions = '<div class="flex justify-center space-x-1">';
                    
                    // View button
                    $actions .= '<a href="' . route('admin.bookings.show', $booking->id) . '" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-all duration-200 group" 
                                   title="View Details">
                                    <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                                </a>';
                    
                    // Edit button
                    $actions .= '<a href="' . route('admin.bookings.edit', $booking->id) . '" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-all duration-200 group" 
                                   title="Edit Booking">
                                    <i class="fas fa-edit text-xs group-hover:scale-110 transition-transform"></i>
                                </a>';
                    
                    // Delete button
                    $actions .= '<button onclick="deleteBooking(' . $booking->id . ')" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-all duration-200 group" 
                                   title="Delete Booking">
                                    <i class="fas fa-trash text-xs group-hover:scale-110 transition-transform"></i>
                                </button>';
                    
                    $actions .= '</div>';
                    
                    return $actions;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.bookings.index');
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'service'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::with(['user', 'service'])->findOrFail($id);
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update($request->only(['status', 'notes']));
        
        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully');
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booking: ' . $e->getMessage()
            ], 500);
        }
    }

    // Export methods
    public function exportPdf(Request $request)
    {
        $query = Booking::with(['user', 'service'])->orderBy('created_at', 'desc');
        return $this->exportPDF($request, $query, 'admin.exports.bookings_pdf');
    }

    public function exportExcel(Request $request)
    {
        return $this->exportExcel($request, BookingsExport::class);
    }

    public function exportCsv(Request $request)
    {
        return $this->exportCSV($request, BookingsExport::class);
    }

    public function print(Request $request)
    {
        $query = Booking::with(['user', 'service'])->orderBy('created_at', 'desc');
        return $this->printView($request, $query, 'admin.exports.bookings_print');
    }

    protected function getModelName(): string
    {
        return 'bookings';
    }

    protected function getExportTitle(): string
    {
        return 'Data Booking';
    }
}
