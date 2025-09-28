<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\PairwiseComparison;

class AhpManagementController extends Controller
{
    /**
     * Display the AHP management page.
     */
    public function index()
    {
        $criteria = Criteria::all();
        $comparisons = PairwiseComparison::with(['criterion1', 'criterion2'])->get();
        return view('admin.ahp-management', compact('criteria', 'comparisons'));
    }

    // CRUD Kriteria
    public function storeCriterion(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Criteria::create(['name' => $request->name]);
        return redirect()->route('admin.ahp-management')->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function updateCriterion(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $criterion = Criteria::findOrFail($id);
        $criterion->update(['name' => $request->name]);
        return redirect()->route('admin.ahp-management')->with('success', 'Kriteria berhasil diupdate');
    }

    public function destroyCriterion($id)
    {
        Criteria::destroy($id);
        return redirect()->route('admin.ahp-management')->with('success', 'Kriteria berhasil dihapus');
    }

    // CRUD Pairwise Comparison
    public function storeComparison(Request $request)
    {
        $request->validate([
            'criterion_id_1' => 'required|exists:criteria,id',
            'criterion_id_2' => 'required|exists:criteria,id',
            'value' => 'required|numeric|min:1|max:9',
        ]);
        PairwiseComparison::create($request->only(['criterion_id_1', 'criterion_id_2', 'value']));
        return redirect()->route('admin.ahp-management')->with('success', 'Perbandingan berhasil ditambahkan');
    }

    public function updateComparison(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|numeric|min:1|max:9',
        ]);
        $comparison = PairwiseComparison::findOrFail($id);
        $comparison->update(['value' => $request->value]);
        return redirect()->route('admin.ahp-management')->with('success', 'Perbandingan berhasil diupdate');
    }

    public function destroyComparison($id)
    {
        PairwiseComparison::destroy($id);
        return redirect()->route('admin.ahp-management')->with('success', 'Perbandingan berhasil dihapus');
    }
}
