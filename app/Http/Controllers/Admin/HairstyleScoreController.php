<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hairstyle;
use App\Models\HairstyleScore;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HairstyleScoreController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        \Log::info('Fetching hairstyle scores for DataTable');
        
        $data = HairstyleScore::with(['hairstyle', 'criterion'])->latest()->get();

        \Log::info('Hairstyle scores fetched for DataTable', [
            'total' => $data->count(),
            'first_item' => $data->first()?->id,
        ]);

        return DataTables::of($data)
    ->addIndexColumn()
    ->editColumn('hairstyle', function ($row) {
        // Akses relasi hairstyle_id ke model Hairstyle
        $hairstyleName = $row->hairstyle ? $row->hairstyle->name : 'N/A';
        \Log::debug('Processing hairstyle column', [
            'score_id' => $row->id, 
            'hairstyle_id' => $row->hairstyle_id,
            'hairstyle_name' => $hairstyleName
        ]);
        
        return '<div class="flex items-center gap-2">
            <i class="fas fa-cut text-lg text-purple-600"></i>
            <span>'.$hairstyleName.'</span>
        </div>';
    })
    ->editColumn('criterion', function ($row) {
        // Akses relasi criterion_id ke model Criteria
        $criterionName = $row->criterion ? $row->criterion->name : 'N/A';
        \Log::debug('Processing criterion column', [
            'score_id' => $row->id, 
            'criterion_id' => $row->criterion_id,
            'criterion_name' => $criterionName
        ]);
        
        return '<div class="flex items-center gap-2">
            <i class="fas fa-star text-sm text-yellow-500"></i>
            <span>'.$criterionName.'</span>
        </div>';
    })
    ->editColumn('score', function ($row) {
        \Log::debug('Processing score column', [
            'score_id' => $row->id, 
            'score_value' => $row->score
        ]);
        
        // Normalisasi score ke persentase (asumsi score 0-100)
        $percentage = min(max($row->score, 0), 100);
        
        return '<div class="flex items-center gap-2">
            <div class="w-16 bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: '.$percentage.'%"></div>
            </div>
            <span>'.$row->score.'</span>
        </div>';
    })
    ->addColumn('action', function ($row) {
        $editUrl = route('admin.hairstyles.score.edit', $row->id);
        return '
            <div class="flex justify-center items-center gap-2">
                <a href="'.$editUrl.'" 
                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200" 
                   title="Edit Score">
                    <i class="fas fa-edit text-sm"></i>
                </a>
                <button type="button" 
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200 deleteBtn" 
                        data-id="'.$row->id.'" 
                        title="Delete Score">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>';
    })
    ->rawColumns(['hairstyle', 'criterion', 'score', 'action'])
    ->make(true);
    }

    return view('admin.hairstyles.scores.index');
}

    public function create()
    {
        $hairstyles = Hairstyle::all();
        $criteria   = Criteria::all();
        return view('admin.hairstyles.scores.create', compact('hairstyles', 'criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hairstyle_id' => 'required|exists:hairstyles,id',
            'criterion_id' => 'required|exists:criteria,id',
            'score'        => 'required|numeric|min:0',
        ]);

        HairstyleScore::create($request->only(['hairstyle_id', 'criterion_id', 'score']));

        return redirect()->route('admin.hairstyles.score.index')
                         ->with('success', __('admin.hairstyle_score_created_successfully'));
    }

    public function edit(HairstyleScore $hairstyle_score)
    {
        $hairstyles = Hairstyle::all();
        $criteria   = Criteria::all();
        return view('admin.hairstyles.scores.edit', compact('hairstyle_score', 'hairstyles', 'criteria'));
    }

    public function update(Request $request, HairstyleScore $hairstyle_score)
    {
        $request->validate([
            'hairstyle_id' => 'required|exists:hairstyles,id',
            'criterion_id' => 'required|exists:criteria,id',
            'score'        => 'required|numeric|min:0',
        ]);

        $hairstyle_score->update($request->only(['hairstyle_id', 'criterion_id', 'score']));

        return redirect()->route('admin.hairstyles.score.index')
                         ->with('success', __('admin.hairstyle_score_updated_successfully'));
    }

    public function destroy(HairstyleScore $hairstyle_score)
    {
        try {
            $hairstyle_score->delete();
            
            return response()->json([
                'success' => true,
                'message' => __('admin.delete_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.error_occurred')
            ], 500);
        }
    }
}
