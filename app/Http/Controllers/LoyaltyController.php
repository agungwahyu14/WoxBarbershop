<?php

namespace App\Http\Controllers;

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
                return '<span class="font-semibold text-yellow-700">' . $row->points . '</span>';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name ?? '-';
            })
           ->addColumn('action', function ($row) {
    $showUrl = route('loyalties.show', $row->id);

    return '<a href="' . $showUrl . '" 
               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-green-600 transition-all duration-200 group" 
               title="View Details">
                <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
            </a>';
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
}
