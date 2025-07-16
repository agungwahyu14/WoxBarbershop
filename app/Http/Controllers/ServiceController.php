<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('price', fn($row) => 'Rp ' . number_format($row->price, 0, ',', '.'))
                ->addColumn('action', function ($row) {
                    $editUrl = route('services.edit', $row->id);
                    $deleteUrl = route('services.destroy', $row->id);

                    return '
<div class="flex justify-center items-center gap-2">
    <a href="' . $editUrl . '" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition deleteBtn" data-id="' . $row->id . '" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.services.index');
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0'
        ]);

        Service::create($request->only('name', 'description', 'price'));

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0'
        ]);

        $service->update($request->only('name', 'description', 'price'));

        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['success' => true, 'message' => 'Layanan berhasil dihapus.']);
    }
}

