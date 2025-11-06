<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\ExportTrait;
use App\Exports\ServicesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view services', ['only' => ['index', 'show']]);
        $this->middleware('permission:create services', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit services', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete services', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Service::query();

            // Apply search filter
            if ($request->has('search') && ! empty($request->search['value'])) {
                $searchTerm = $request->search['value'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('name_id', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('name_en', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('description_id', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('description_en', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('price', 'LIKE', "%{$searchTerm}%");
                });
            }

            // // Apply category filter
            // if ($request->has('category_filter') && ! empty($request->category_filter)) {
            //     $query->where('category', $request->category_filter);
            // }

            // Apply price range filter
            if ($request->has('price_min') && ! empty($request->price_min)) {
                $query->where('price', '>=', $request->price_min);
            }
            if ($request->has('price_max') && ! empty($request->price_max)) {
                $query->where('price', '<=', $request->price_max);
            }

            // Apply month filter
            if ($request->has('month_filter') && ! empty($request->month_filter)) {
                $query->whereMonth('created_at', $request->month_filter);
            }

            // Apply year filter
            if ($request->has('year_filter') && ! empty($request->year_filter)) {
                $query->whereYear('created_at', $request->year_filter);
            }

            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $icon = $this->getServiceIcon($row->name);
                    $status = $row->is_active ? 'Active' : 'Inactive';
                    $statusColor = $row->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

                    return '<div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="'.$icon.' text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">'.e($row->name).'</div>
                            
                        </div>
                    </div>';
                })
                ->editColumn('category', function ($row) {
                    $categoryColors = [
                        'haircut' => 'bg-blue-100 text-blue-800',
                        'styling' => 'bg-purple-100 text-purple-800',
                        'treatment' => 'bg-green-100 text-green-800',
                        'coloring' => 'bg-pink-100 text-pink-800',
                        'other' => 'bg-gray-100 text-gray-800',
                    ];

                    $color = $categoryColors[$row->category ?? 'other'] ?? $categoryColors['other'];

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$color.'">
                        '.ucfirst($row->category ?? 'Other').'
                    </span>';
                })
                ->editColumn('price', function ($row) {
                    return '<div class="text-right">
                        <div class="text-lg font-bold text-green-600">Rp '.number_format($row->price, 0, ',', '.').'</div>
                
                    </div>';
                })
                ->editColumn('duration', function ($row) {
                    if ($row->duration) {
                        return '<div class="flex items-center space-x-1">
                            <i class="fas fa-clock text-blue-500"></i>
                            <span class="text-sm">'.$row->duration.' min</span>
                        </div>';
                    }

                    return '<span class="text-gray-400 text-sm">Not set</span>';
                })
                ->editColumn('description', function ($row) {
                    if ($row->description) {
                        return '<div class="max-w-xs">
                            <p class="text-sm text-gray-600 truncate" title="'.e($row->description).'">
                                '.e($row->description).'
                            </p>
                        </div>';
                    }

                    return '<span class="text-gray-400 text-sm">No description</span>';
                })
                ->addColumn('action', function ($row) {
                    // $showUrl = route('admin.services.show', $row->id);
                    $editUrl = route('admin.services.edit', $row->id);

                    $actions = '<div class="flex justify-center items-center space-x-1">';

             
                    if (auth()->user()->can('edit services')) {
                        $actions .= '<a href="'.$editUrl.'" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-all duration-200 group" 
                                       title="Edit Service">
                                        <i class="fas fa-edit text-xs group-hover:scale-110 transition-transform"></i>
                                    </a>';
                    }

                    if (auth()->user()->can('delete services')) {
                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-all duration-200 group deleteBtn" 
                                            data-id="'.$row->id.'" 
                                            title="Delete Service">
                                        <i class="fas fa-trash text-xs group-hover:scale-110 transition-transform"></i>
                                    </button>';
                    }

                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['name', 'price', 'duration', 'description', 'action'])
                ->make(true);
        }

        // Get statistics
        $stats = [
            'total_services' => Service::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'inactive_services' => Service::where('is_active', false)->count(),
            'average_price' => Service::where('is_active', true)->avg('price') ?? 0,
        ];

        // $categories = Service::distinct()->pluck('category')->filter()->values();

        return view('admin.services.index', compact('stats'));
    }

    public function show(Service $service)
    {
        $service->load(['bookings' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('admin.services.show', compact('service'));
    }

    public function create()
    {

        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services',
            'description' => 'nullable|string|max:1000',
            'name_id' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_id' => 'nullable|string|max:1000',
            'description_en' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:10000000',
            'duration' => 'nullable|integer|min:1|max:480',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'name_id' => $validated['name_id'],
                'name_en' => $validated['name_en'],
                'description_id' => $validated['description_id'],
                'description_en' => $validated['description_en'],
                'price' => $validated['price'],
                'duration' => $validated['duration'],
                'is_active' => $request->boolean('is_active', true),
            ];

            Service::create($data);

            DB::commit();

            return redirect()->route('admin.services.index')
                ->with('success', __('admin.service_created_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Service creation failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', __('admin.service_create_failed'));
        }
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,'.$service->id,
            'description' => 'nullable|string|max:1000',
            'name_id' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_id' => 'nullable|string|max:1000',
            'description_en' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:10000000',
            'duration' => 'nullable|integer|min:1|max:480',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'name_id' => $validated['name_id'],
                'name_en' => $validated['name_en'],
                'description_id' => $validated['description_id'],
                'description_en' => $validated['description_en'],
                'price' => $validated['price'],
                'duration' => $validated['duration'],
                'is_active' => $request->boolean('is_active', true),
            ];

            

            $service->update($data);

            DB::commit();

            return redirect()->route('admin.services.index')
                ->with('success', __('admin.service_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Service update failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', __('admin.service_update_failed'));
        }
    }

    public function destroy(Service $service)
    {
        try {
            DB::beginTransaction();

            // Check if service is used in bookings
            if ($service->bookings && $service->bookings->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.service_delete_has_bookings'),
                ], 422);
            }

            // Delete image if exists
          

            $service->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('admin.service_deleted_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Service deletion failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('admin.service_delete_failed'),
            ], 500);
        }
    }

    /**
     * Toggle service status
     */
    public function toggleStatus(Request $request, Service $service)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate',
        ]);

        try {
            $isActivating = $request->action === 'activate';

            $service->update([
                'is_active' => $isActivating,
            ]);

            return response()->json([
                'success' => true,
                'message' => $isActivating ? __('admin.service_activated_successfully') : __('admin.service_deactivated_successfully'),
            ]);

        } catch (\Exception $e) {
            Log::error('Service status toggle failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('admin.service_status_update_failed'),
            ], 500);
        }
    }

    /**
     * Get service statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => Service::count(),
                'active' => Service::where('is_active', true)->count(),
                'inactive' => Service::where('is_active', false)->count(),
                'categories' => Service::select('category', DB::raw('count(*) as count'))
                    ->groupBy('category')
                    ->pluck('count', 'category')
                    ->toArray(),
                'average_price' => Service::where('is_active', true)->avg('price') ?? 0,
                'price_range' => [
                    'min' => Service::where('is_active', true)->min('price') ?? 0,
                    'max' => Service::where('is_active', true)->max('price') ?? 0,
                ],
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Service stats retrieval failed: '.$e->getMessage());

            return response()->json([
                'error' => 'Failed to retrieve statistics.',
            ], 500);
        }
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

        return 'fas fa-scissors';
    }

    // Export methods
    public function exportPdf(Request $request)
    {
        $query = Service::orderBy('created_at', 'desc');
        return $this->exportPDF($request, $query, 'admin.exports.services_pdf');
    }

    public function exportExcel(Request $request)
    {
        return $this->exportExcel($request, ServicesExport::class);
    }

    public function exportCsv(Request $request)
    {
        return $this->exportCSV($request, ServicesExport::class);
    }

    public function print(Request $request)
    {
        $query = Service::orderBy('created_at', 'desc');
        return $this->printView($request, $query, 'admin.exports.services_print');
    }

    protected function getModelName(): string
    {
        return 'services';
    }

    protected function getExportTitle(): string
    {
        return 'Data Layanan';
    }
}
