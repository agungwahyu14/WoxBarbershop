<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $icon = $this->getServiceIcon($row->name);

                    return '<div class="flex items-center gap-2">
                        <i class="'.$icon.' text-lg text-blue-600"></i>
                        <span>'.$row->name.'</span>
                    </div>';
                })
                ->editColumn('price', function ($row) {
                    return '<span class="font-semibold text-green-600">Rp '.number_format($row->price, 0, ',', '.').'</span>';
                })
                ->editColumn('description', function ($row) {
                    return $row->description ? '<div class="max-w-xs truncate" title="'.$row->description.'">'.$row->description.'</div>' : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('services.edit', $row->id);

                    return '
                        <div class="flex justify-center items-center gap-2">
                            <a href="'.$editUrl.'" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200" 
                               title="Edit Service">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button type="button" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200 deleteBtn" 
                                    data-id="'.$row->id.'" 
                                    title="Delete Service">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>';
                })
                ->rawColumns(['name', 'price', 'description', 'action'])
                ->make(true);
        }

        return view('admin.services.index');
    }

    private function getServiceIcon($serviceName)
    {
        $serviceName = strtolower($serviceName);

        $icons = [
            'potong rambut' => 'fas fa-cut',
            'hair cut' => 'fas fa-cut',
            'cukur' => 'fas fa-cut',
            'shampoo' => 'fas fa-soap',
            'cuci rambut' => 'fas fa-soap',
            'styling' => 'fas fa-magic',
            'hair styling' => 'fas fa-magic',
            'pewarnaan' => 'fas fa-palette',
            'hair color' => 'fas fa-palette',
            'coloring' => 'fas fa-palette',
            'creambath' => 'fas fa-bath',
            'treatment' => 'fas fa-spa',
            'facial' => 'fas fa-user-circle',
            'massage' => 'fas fa-hand-sparkles',
            'keratin' => 'fas fa-fire',
            'smoothing' => 'fas fa-wind',
            'perm' => 'fas fa-snowflake',
        ];

        foreach ($icons as $keyword => $icon) {
            if (strpos($serviceName, $keyword) !== false) {
                return $icon;
            }
        }

        return 'fas fa-scissors'; // Default barber icon
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
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
