<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hairstyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class HairstyleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view hairstyles', ['only' => ['index', 'show']]);
        $this->middleware('permission:create hairstyles', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit hairstyles', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete hairstyles', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Hairstyle::query();

            // Apply search filter
            if ($request->has('search') && ! empty($request->search['value'])) {
                $searchTerm = $request->search['value'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('bentuk_kepala', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('tipe_rambut', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Apply face shape filter
            if ($request->has('face_shape_filter') && ! empty($request->face_shape_filter)) {
                $query->where('bentuk_kepala', $request->face_shape_filter);
            }

            // Apply hair type filter
            if ($request->has('hair_type_filter') && ! empty($request->hair_type_filter)) {
                $query->where('tipe_rambut', $request->hair_type_filter);
            }

            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $icon = $this->getHairstyleIcon($row->name);
                    $status = $row->is_active ? 'Available' : 'Unavailable';
                    $statusColor = $row->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

                    return '<div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="'.$icon.' text-purple-600"></i>
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">'.e($row->name).'</div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium '.$statusColor.'">
                                '.$status.'
                            </span>
                        </div>
                    </div>';
                })
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<div class="flex justify-center">
                            <img src="'.Storage::url($row->image).'" 
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm" 
                                 alt="'.e($row->name).'" />
                        </div>';
                    }

                    return '<div class="flex justify-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    </div>';
                })
                ->editColumn('bentuk_kepala', function ($row) {
                    $icon = $this->getFaceShapeIcon($row->bentuk_kepala);
                    $faceShapes = [
                        'bulat' => 'Round Face',
                        'oval' => 'Oval Face',
                        'persegi' => 'Square Face',
                        'heart' => 'Heart Face',
                        'diamond' => 'Diamond Face',
                        'oblong' => 'Oblong Face',
                    ];

                    $displayName = $faceShapes[$row->bentuk_kepala] ?? ucfirst($row->bentuk_kepala);

                    return '<div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="'.$icon.' text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">'.$displayName.'</span>
                    </div>';
                })
                ->editColumn('tipe_rambut', function ($row) {
                    $icon = $this->getHairTypeIcon($row->tipe_rambut);
                    $hairTypes = [
                        'lurus' => 'Straight',
                        'bergelombang' => 'Wavy',
                        'keriting' => 'Curly',
                        'ikal' => 'Coily',
                        'tebal' => 'Thick',
                        'tipis' => 'Thin',
                    ];

                    $displayName = $hairTypes[$row->tipe_rambut] ?? ucfirst($row->tipe_rambut);

                    return '<div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="'.$icon.' text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">'.$displayName.'</span>
                    </div>';
                })
                ->editColumn('difficulty', function ($row) {
                    if ($row->difficulty) {
                        $difficultyColors = [
                            'easy' => 'bg-green-100 text-green-800',
                            'medium' => 'bg-yellow-100 text-yellow-800',
                            'hard' => 'bg-red-100 text-red-800',
                        ];

                        $color = $difficultyColors[$row->difficulty] ?? $difficultyColors['medium'];

                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$color.'">
                            '.ucfirst($row->difficulty).'
                        </span>';
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
                ->addColumn('stats', function ($row) {
                    $bookingsCount = $row->bookings ? $row->bookings->count() : 0;
                    $popularity = $bookingsCount > 10 ? 'High' : ($bookingsCount > 5 ? 'Medium' : 'Low');
                    $popularityColor = $bookingsCount > 10 ? 'text-green-600' : ($bookingsCount > 5 ? 'text-yellow-600' : 'text-gray-600');

                    return '<div class="text-center">
                        <div class="text-sm font-semibold text-gray-900">'.$bookingsCount.'</div>
                        <div class="text-xs '.$popularityColor.'">'.$popularity.'</div>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.hairstyles.show', $row->id);
                    $editUrl = route('admin.hairstyles.edit', $row->id);

                    $actions = '<div class="flex justify-center items-center space-x-1">';

                    // View button
                    $actions .= '<a href="'.$showUrl.'" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-all duration-200 group" 
                                   title="View Details">
                                    <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                                </a>';

                    // Edit button
                    if (auth()->user()->can('edit hairstyles')) {
                        $actions .= '<a href="'.$editUrl.'" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-all duration-200 group" 
                                       title="Edit Hairstyle">
                                        <i class="fas fa-edit text-xs group-hover:scale-110 transition-transform"></i>
                                    </a>';
                    }

                    // Toggle status button
                    if (auth()->user()->can('edit hairstyles')) {
                        $statusAction = $row->is_active ? 'deactivate' : 'activate';
                        $statusColor = $row->is_active ? 'yellow' : 'green';
                        $statusIcon = $row->is_active ? 'pause' : 'play';

                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-'.$statusColor.'-100 hover:bg-'.$statusColor.'-200 text-'.$statusColor.'-600 transition-all duration-200 group toggleStatusBtn" 
                                            data-id="'.$row->id.'" 
                                            data-action="'.$statusAction.'"
                                            title="'.ucfirst($statusAction).' Hairstyle">
                                        <i class="fas fa-'.$statusIcon.' text-xs group-hover:scale-110 transition-transform"></i>
                                    </button>';
                    }

                    // Delete button
                    if (auth()->user()->can('delete hairstyles')) {
                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-all duration-200 group deleteBtn" 
                                            data-id="'.$row->id.'" 
                                            title="Delete Hairstyle">
                                        <i class="fas fa-trash text-xs group-hover:scale-110 transition-transform"></i>
                                    </button>';
                    }

                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['name', 'image', 'bentuk_kepala', 'tipe_rambut', 'difficulty', 'description', 'stats', 'action'])
                ->make(true);
        }

        // Get statistics
        $stats = [
            'total_hairstyles' => Hairstyle::count(),
            'active_hairstyles' => Hairstyle::where('is_active', true)->count(),
            'inactive_hairstyles' => Hairstyle::where('is_active', false)->count(),
            'popular_hairstyles' => Hairstyle::withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->limit(5)
                ->get(),
        ];

        $faceShapes = Hairstyle::distinct()->pluck('bentuk_kepala')->filter()->values();
        $hairTypes = Hairstyle::distinct()->pluck('tipe_rambut')->filter()->values();

        return view('admin.hairstyles.index', compact('stats', 'faceShapes', 'hairTypes'));
    }

    public function show(Hairstyle $hairstyle)
    {
        $hairstyle->load(['bookings' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('admin.hairstyles.show', compact('hairstyle'));
    }

    public function create()
    {
        $faceShapes = [
            'bulat' => 'Round Face',
            'oval' => 'Oval Face',
            'persegi' => 'Square Face',
            'heart' => 'Heart Face',
            'diamond' => 'Diamond Face',
            'oblong' => 'Oblong Face',
        ];

        $hairTypes = [
            'lurus' => 'Straight Hair',
            'bergelombang' => 'Wavy Hair',
            'keriting' => 'Curly Hair',
            'ikal' => 'Coily Hair',
            'tebal' => 'Thick Hair',
            'tipis' => 'Thin Hair',
        ];

        $difficulties = [
            'easy' => 'Easy',
            'medium' => 'Medium',
            'hard' => 'Hard',
        ];

        return view('admin.hairstyles.create', compact('faceShapes', 'hairTypes', 'difficulties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:hairstyles',
            'description' => 'nullable|string|max:1000',
            'bentuk_kepala' => 'required|string|in:bulat,oval,persegi,heart,diamond,oblong',
            'tipe_rambut' => 'required|string|in:lurus,bergelombang,keriting,ikal,tebal,tipis',
            'difficulty' => 'nullable|string|in:easy,medium,hard',
            'maintenance_level' => 'nullable|string|in:low,medium,high',
            'styling_time' => 'nullable|integer|min:1|max:180',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'bentuk_kepala' => $validated['bentuk_kepala'],
                'tipe_rambut' => $validated['tipe_rambut'],
                'difficulty' => $validated['difficulty'],
                'maintenance_level' => $validated['maintenance_level'],
                'styling_time' => $validated['styling_time'],
                'is_active' => $request->boolean('is_active', true),
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('hairstyles', 'public');
            }

            Hairstyle::create($data);

            DB::commit();

            return redirect()->route('admin.hairstyles.index')
                ->with('success', 'Hairstyle created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Hairstyle creation failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to create hairstyle. Please try again.');
        }
    }

    public function edit(Hairstyle $hairstyle)
    {
        $faceShapes = [
            'bulat' => 'Round Face',
            'oval' => 'Oval Face',
            'persegi' => 'Square Face',
            'heart' => 'Heart Face',
            'diamond' => 'Diamond Face',
            'oblong' => 'Oblong Face',
        ];

        $hairTypes = [
            'lurus' => 'Straight Hair',
            'bergelombang' => 'Wavy Hair',
            'keriting' => 'Curly Hair',
            'ikal' => 'Coily Hair',
            'tebal' => 'Thick Hair',
            'tipis' => 'Thin Hair',
        ];

        $difficulties = [
            'easy' => 'Easy',
            'medium' => 'Medium',
            'hard' => 'Hard',
        ];

        return view('admin.hairstyles.edit', compact('hairstyle', 'faceShapes', 'hairTypes', 'difficulties'));
    }

    public function update(Request $request, Hairstyle $hairstyle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:hairstyles,name,'.$hairstyle->id,
            'description' => 'nullable|string|max:1000',
            'bentuk_kepala' => 'required|string|in:bulat,oval,persegi,heart,diamond,oblong',
            'tipe_rambut' => 'required|string|in:lurus,bergelombang,keriting,ikal,tebal,tipis',
            'difficulty' => 'nullable|string|in:easy,medium,hard',
            'maintenance_level' => 'nullable|string|in:low,medium,high',
            'styling_time' => 'nullable|integer|min:1|max:180',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'bentuk_kepala' => $validated['bentuk_kepala'],
                'tipe_rambut' => $validated['tipe_rambut'],
                'difficulty' => $validated['difficulty'],
                'maintenance_level' => $validated['maintenance_level'],
                'styling_time' => $validated['styling_time'],
                'is_active' => $request->boolean('is_active', true),
            ];

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($hairstyle->image) {
                    Storage::disk('public')->delete($hairstyle->image);
                }

                $data['image'] = $request->file('image')->store('hairstyles', 'public');
            }

            $hairstyle->update($data);

            DB::commit();

            return redirect()->route('admin.hairstyles.index')
                ->with('success', 'Hairstyle updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Hairstyle update failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to update hairstyle. Please try again.');
        }
    }

    public function destroy(Hairstyle $hairstyle)
    {
        try {
            DB::beginTransaction();

            // Check if hairstyle is used in bookings
            if ($hairstyle->bookings && $hairstyle->bookings->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete hairstyle that has associated bookings.',
                ], 422);
            }

            // Delete image if exists
            if ($hairstyle->image) {
                Storage::disk('public')->delete($hairstyle->image);
            }

            $hairstyle->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hairstyle deleted successfully.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Hairstyle deletion failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete hairstyle. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle hairstyle status
     */
    public function toggleStatus(Request $request, Hairstyle $hairstyle)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate',
        ]);

        try {
            $isActivating = $request->action === 'activate';

            $hairstyle->update([
                'is_active' => $isActivating,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hairstyle '.($isActivating ? 'activated' : 'deactivated').' successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Hairstyle status toggle failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update hairstyle status.',
            ], 500);
        }
    }

    private function getHairstyleIcon($hairstyleName)
    {
        $hairstyleName = strtolower($hairstyleName);

        $icons = [
            'buzz cut' => 'fas fa-cut',
            'crew cut' => 'fas fa-cut',
            'fade' => 'fas fa-cut',
            'undercut' => 'fas fa-cut',
            'pompadour' => 'fas fa-crown',
            'quiff' => 'fas fa-crown',
            'slick back' => 'fas fa-arrow-right',
            'side part' => 'fas fa-divide',
            'textured' => 'fas fa-grip-lines',
            'messy' => 'fas fa-wind',
            'wavy' => 'fas fa-water',
            'curly' => 'fas fa-circle-notch',
            'straight' => 'fas fa-minus',
            'spiky' => 'fas fa-mountain',
            'mohawk' => 'fas fa-fire',
            'comb over' => 'fas fa-exchange-alt',
        ];

        foreach ($icons as $keyword => $icon) {
            if (strpos($hairstyleName, $keyword) !== false) {
                return $icon;
            }
        }

        return 'fas fa-cut';
    }

    private function getFaceShapeIcon($faceShape)
    {
        $faceShape = strtolower($faceShape);

        $icons = [
            'bulat' => 'fas fa-circle',
            'round' => 'fas fa-circle',
            'oval' => 'fas fa-egg',
            'persegi' => 'fas fa-square',
            'square' => 'fas fa-square',
            'heart' => 'fas fa-heart',
            'diamond' => 'fas fa-gem',
            'oblong' => 'fas fa-rectangle',
            'long' => 'fas fa-rectangle',
        ];

        return $icons[$faceShape] ?? 'fas fa-user';
    }

    private function getHairTypeIcon($hairType)
    {
        $hairType = strtolower($hairType);

        $icons = [
            'lurus' => 'fas fa-minus',
            'straight' => 'fas fa-minus',
            'bergelombang' => 'fas fa-wave-square',
            'wavy' => 'fas fa-wave-square',
            'keriting' => 'fas fa-circle-notch',
            'curly' => 'fas fa-circle-notch',
            'ikal' => 'fas fa-sync',
            'tebal' => 'fas fa-align-justify',
            'thick' => 'fas fa-align-justify',
            'tipis' => 'fas fa-align-left',
            'thin' => 'fas fa-align-left',
        ];

        return $icons[$hairType] ?? 'fas fa-cut';
    }
}
