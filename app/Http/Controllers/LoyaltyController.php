<?php

namespace App\Http\Controllers;

use App\Models\Loyalty;
use Illuminate\Http\Request;

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
                return '<a href="' . route('loyalties.show', $row->id) . '" class="btn btn-sm btn-info">Lihat</a>';
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
        //
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
