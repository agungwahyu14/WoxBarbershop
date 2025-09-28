<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hairstyle;
use App\Models\BentukKepala;
use App\Models\TipeRambut;
use App\Models\StylePreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;


class HairstyleController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        // Ambil data dengan relasi
        \Log::info('Fetching hairstyles for DataTable');
        $bentuk_kepala = BentukKepala::all();
        $tipe_rambut = TipeRambut::all();
        $style_preference = StylePreference::all();
        $data = Hairstyle::with(['bentuk_kepala', 'tipe_rambut', 'style_preference'])->latest()->get();

        // Logging jumlah data dan beberapa contoh
        \Log::info('Hairstyles fetched for DataTable', [
            'total' => $data->count(),
            'first_item' => $data->first()?->name,
        ]);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $icon = $this->getHairstyleIcon($row->name);
                \Log::debug('Processing name column', ['hairstyle_id' => $row->id, 'name' => $row->name]);
                return '<div class="flex items-center gap-2">
                    <i class="'.$icon.' text-lg text-purple-600"></i>
                    <span>'.$row->name.'</span>
                </div>';
            })
            ->editColumn('bentuk_kepala', function ($row) {
                $names = $row->bentuk_kepala->pluck('nama')->implode(', ');
                \Log::debug('Processing bentuk_kepala', ['hairstyle_id' => $row->id, 'bentuk_kepala' => $names]);
                return '<div class="flex items-center gap-2">
                    <i class="fas fa-user text-sm text-gray-600"></i>
                    <span class="capitalize">'.$names.'</span>
                </div>';
            })
            ->editColumn('tipe_rambut', function ($row) {
                $names = $row->tipe_rambut->pluck('nama')->implode(', ');
                \Log::debug('Processing tipe_rambut', ['hairstyle_id' => $row->id, 'tipe_rambut' => $names]);
                return '<div class="flex items-center gap-2">
                    <i class="fas fa-cut text-sm text-gray-600"></i>
                    <span class="capitalize">'.$names.'</span>
                </div>';
            })
            ->editColumn('style_preference', function ($row) {
                $names = $row->style_preference->pluck('nama')->implode(', ');
                \Log::debug('Processing style_preference', ['hairstyle_id' => $row->id, 'style_preference' => $names]);
                return '<span>'.$names.'</span>';
            })
            ->editColumn('description', function ($row) {
                return $row->description 
                    ? '<div class="max-w-xs truncate" title="'.$row->description.'">'.$row->description.'</div>' 
                    : '-';
            })
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    $url = asset('storage/'.$row->image);
                    return '<div class="flex justify-center">
                        <img src="'.$url.'" 
                             class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-200" 
                             alt="'.$row->name.'" 
                             onerror="this.src=\'/img/placeholder.svg\'; this.classList.add(\'opacity-50\')" />
                    </div>';
                }
                return '<div class="flex justify-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                </div>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('admin.hairstyles.edit', $row->id);
                return '
                    <div class="flex justify-center items-center gap-2">
                        <a href="'.$editUrl.'" 
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200" 
                           title="Edit Hairstyle">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <button type="button" 
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200 deleteBtn" 
                                data-id="'.$row->id.'" 
                                title="Delete Hairstyle">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['name', 'bentuk_kepala', 'tipe_rambut', 'style_preference', 'description', 'image', 'action'])
            ->make(true);
    }

    return view('admin.hairstyles.index');
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

        return 'fas fa-cut'; // Default hairstyle icon
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

    public function create()
    {
        return view('admin.hairstyles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bentuk_kepala' => 'required|string|max:255',
            'tipe_rambut' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'bentuk_kepala', 'tipe_rambut']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hairstyles', 'public');
        }

        Hairstyle::create($data);

        return redirect()->route('admin.hairstyles.index')->with('success', 'Hairstyle created successfully.');
    }

    public function edit(Hairstyle $hairstyle)
    {
        return view('admin.hairstyles.edit', compact('hairstyle'));
    }

    public function update(Request $request, Hairstyle $hairstyle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bentuk_kepala' => 'required|string|max:255',
            'tipe_rambut' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'bentuk_kepala', 'tipe_rambut']);

        if ($request->hasFile('image')) {
            if ($hairstyle->image) {
                Storage::disk('public')->delete($hairstyle->image);
            }

            $data['image'] = $request->file('image')->store('hairstyles', 'public');
        }

        $hairstyle->update($data);

        return redirect()->route('admin.hairstyles.index')->with('success', 'Hairstyle updated successfully.');
    }

    public function destroy(Hairstyle $hairstyle)
    {
        if ($hairstyle->image) {
            Storage::disk('public')->delete($hairstyle->image);
        }

        $hairstyle->delete();

        return response()->json(['success' => true, 'message' => 'Hairstyle deleted successfully.']);
    }
}
