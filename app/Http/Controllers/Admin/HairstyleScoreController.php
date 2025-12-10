<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hairstyle;
use App\Models\HairstyleScore;
use App\Models\Criteria;
use App\Models\BentukKepala;
use App\Models\TipeRambut;
use App\Models\StylePreference;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HairstyleScoreController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = HairstyleScore::with(['hairstyle', 'criterion'])->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('hairstyle', function ($row) {
                    $hairstyleName = $row->hairstyle ? $row->hairstyle->name : 'N/A';
                    
                    return '<div class="flex items-center gap-2">
                        <i class="fas fa-cut text-lg text-purple-600"></i>
                        <span class="font-medium">'.$hairstyleName.'</span>
                    </div>';
                })
    ->editColumn('criterion', function ($row) {
        $criterionName = $row->criterion ? $row->criterion->name : 'N/A';
        return '<div class="flex items-center gap-2">
            <i class="fas fa-star text-sm text-yellow-500"></i>
            <span class="font-medium">'.$criterionName.'</span>
        </div>';
    })
    ->addColumn('sub_criterion', function ($row) {
        $subCriterionName = 'N/A';
        $icon = 'fa-tag';
        $color = 'text-gray-500';
        
        // Ambil sub-criterion berdasarkan criterion_id
        if ($row->criterion_id == 8) {
            // Bentuk Kepala
            $subCriterion = BentukKepala::find($row->sub_criterion_id);
            $subCriterionName = $subCriterion ? $subCriterion->nama : 'N/A';
            $icon = 'fa-head-side-virus';
            $color = 'text-blue-500';
        } elseif ($row->criterion_id == 9) {
            // Tipe Rambut
            $subCriterion = TipeRambut::find($row->sub_criterion_id);
            $subCriterionName = $subCriterion ? $subCriterion->nama : 'N/A';
            $icon = 'fa-hands-wash';
            $color = 'text-green-500';
        } elseif ($row->criterion_id == 10) {
            // Preferensi Gaya
            $subCriterion = StylePreference::find($row->sub_criterion_id);
            $subCriterionName = $subCriterion ? $subCriterion->nama : 'N/A';
            $icon = 'fa-palette';
            $color = 'text-purple-500';
        }
        
        return '<div class="flex items-center gap-2">
            <i class="fas '.$icon.' text-sm '.$color.'"></i>
            <span class="font-medium">'.$subCriterionName.'</span>
        </div>';
    })
    ->editColumn('score', function ($row) {
        // Score 1-10, convert to percentage for visual
        $percentage = ($row->score / 10) * 100;
        $color = $row->score >= 7 ? 'green' : ($row->score >= 5 ? 'yellow' : 'red');
        
        return '<div class="flex items-center gap-2">
            <div class="w-20 bg-gray-200 rounded-full h-2.5">
                <div class="bg-'.$color.'-600 h-2.5 rounded-full" style="width: '.$percentage.'%"></div>
            </div>
            <span class="font-semibold text-'.$color.'-600">'.$row->score.'/10</span>
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
                ->rawColumns(['hairstyle', 'criterion', 'sub_criterion', 'score', 'action'])
                ->make(true);
        }

        return view('admin.hairstyles.scores.index');
    }

    public function create()
    {
        $hairstyles = Hairstyle::all();
        $criteria   = Criteria::all();
        
        // Prepare all sub-criteria for dynamic loading
        $bentukKepala = BentukKepala::all(['id', 'nama as name']);
        $tipeRambut = TipeRambut::all(['id', 'nama as name']);
        $stylePreference = StylePreference::all(['id', 'nama as name']);
        
        return view('admin.hairstyles.scores.create', compact(
            'hairstyles', 
            'criteria',
            'bentukKepala',
            'tipeRambut',
            'stylePreference'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hairstyle_id' => 'required|exists:hairstyles,id',
            'criterion_id' => 'required|exists:criteria,id',
            'sub_criterion_id' => 'required|numeric|min:1',
            'score'        => 'required|numeric|min:0|max:10',
        ]);

        HairstyleScore::create($request->only(['hairstyle_id', 'criterion_id', 'sub_criterion_id', 'score']));

        return redirect()->route('admin.hairstyles.score.index')
                         ->with('success', __('admin.hairstyle_score_created_successfully'));
    }

    public function edit(HairstyleScore $hairstyle_score)
    {
        $hairstyles = Hairstyle::all();
        $criteria   = Criteria::all();
        
        // Prepare all sub-criteria for dynamic loading
        $bentukKepala = BentukKepala::all(['id', 'nama as name']);
        $tipeRambut = TipeRambut::all(['id', 'nama as name']);
        $stylePreference = StylePreference::all(['id', 'nama as name']);
        
        // Get current sub-criterion details
        $currentSubCriterion = null;
        if ($hairstyle_score->criterion_id == 8) {
            $currentSubCriterion = BentukKepala::find($hairstyle_score->sub_criterion_id);
        } elseif ($hairstyle_score->criterion_id == 9) {
            $currentSubCriterion = TipeRambut::find($hairstyle_score->sub_criterion_id);
        } elseif ($hairstyle_score->criterion_id == 10) {
            $currentSubCriterion = StylePreference::find($hairstyle_score->sub_criterion_id);
        }
        
        return view('admin.hairstyles.scores.edit', compact(
            'hairstyle_score', 
            'hairstyles', 
            'criteria',
            'bentukKepala',
            'tipeRambut',
            'stylePreference',
            'currentSubCriterion'
        ));
    }

    public function update(Request $request, HairstyleScore $hairstyle_score)
    {
        $request->validate([
            'hairstyle_id' => 'required|exists:hairstyles,id',
            'criterion_id' => 'required|exists:criteria,id',
            'sub_criterion_id' => 'required|numeric|min:1',
            'score'        => 'required|numeric|min:0|max:10',
        ]);

        $hairstyle_score->update($request->only(['hairstyle_id', 'criterion_id', 'sub_criterion_id', 'score']));

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

    /**
     * Get sub-criteria based on criterion_id (AJAX)
     */
    public function getSubCriteria(Request $request)
    {
        $criterionId = $request->criterion_id;
        $subCriteria = [];

        if ($criterionId == 8) {
            // Bentuk Kepala
            $subCriteria = BentukKepala::all(['id', 'nama as name']);
        } elseif ($criterionId == 9) {
            // Tipe Rambut
            $subCriteria = TipeRambut::all(['id', 'nama as name']);
        } elseif ($criterionId == 10) {
            // Preferensi Gaya
            $subCriteria = StylePreference::all(['id', 'nama as name']);
        }

        return response()->json($subCriteria);
    }
}
