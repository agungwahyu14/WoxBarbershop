<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loyalty;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LoyaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Loyalty::with('user')->get(); // pastikan relasi 'user' didefinisikan di model

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('points', function ($row) {
                    return '<span class="font-semibold text-yellow-700">'.$row->points.'</span>';
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="flex justify-center items-center space-x-2">';
                    
                    // Reset button - always show, but disabled if points < 10
                    if ($row->points >= 10) {
                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-all duration-200 group resetPointsBtn" 
                                            data-id="'.$row->id.'" 
                                            data-user="'.$row->user->name.'" 
                                            data-points="'.$row->points.'" 
                                            title="Reset Points (Potong Gratis)">
                                        <i class="fas fa-gift text-xs group-hover:scale-110 transition-transform"></i>
                                    </button>';
                    } else {
                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed" 
                                            disabled
                                            title="Belum mencapai 10 poin">
                                        <i class="fas fa-gift text-xs"></i>
                                    </button>';
                    }
                    
                    $actions .= '</div>';
                    
                    return $actions;
                })

                ->rawColumns(['points', 'user_name', 'action'])
                ->make(true);
        }

        return view('admin.loyalty.index'); // view berisi HTML DataTables
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Loyalty $loyalty)
    {
        $loyalty->load('user'); // agar relasi user ikut dimuat

        return view('admin.loyalty.show', compact('loyalty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loyalty $loyalty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loyalty $loyalty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loyalty $loyalty)
    {
        //
    }

    /**
     * Reset loyalty points for free haircut redemption
     */
    public function resetPoints(Request $request, $loyaltyId)
    {
        try {
            $loyalty = Loyalty::findOrFail($loyaltyId);
            
            // Check if user has enough points to redeem
            if ($loyalty->points < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggan belum memiliki 10 poin untuk direset.'
                ], 400);
            }

            // Reset points to 0
            $oldPoints = $loyalty->points;
            $loyalty->points = 0;
            $loyalty->save();

            \Log::info('Admin reset loyalty points', [
                'admin_id' => auth()->id(),
                'loyalty_id' => $loyalty->id,
                'user_id' => $loyalty->user_id,
                'points_before' => $oldPoints,
                'points_after' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil reset poin loyalty pelanggan ' . $loyalty->user->name . '. Pelanggan mendapat potong gratis!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to reset loyalty points', [
                'loyalty_id' => $loyaltyId,
                'admin_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal reset poin: ' . $e->getMessage()
            ], 500);
        }
    }
}
