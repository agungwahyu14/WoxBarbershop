<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loyalty;
use App\Traits\ExportTrait;
use App\Exports\LoyaltyExport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LoyaltyController extends Controller
{
    use ExportTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Loyalty::with('user')->orderBy('updated_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('customer', function ($loyalty) {
                    return $loyalty->user->name ?? '-';
                })
                ->addColumn('email', function ($loyalty) {
                    return $loyalty->user->email ?? '-';
                })
                ->addColumn('total_points', function ($loyalty) {
                    return number_format($loyalty->total_points ?? 0, 0, ',', '.');
                })
                ->addColumn('used_points', function ($loyalty) {
                    return number_format($loyalty->used_points ?? 0, 0, ',', '.');
                })
                ->addColumn('remaining_points', function ($loyalty) {
                    $remaining = ($loyalty->total_points ?? 0) - ($loyalty->used_points ?? 0);
                    return number_format($remaining, 0, ',', '.');
                })
                ->addColumn('level', function ($loyalty) {
                    $level = $loyalty->level ?? 'Bronze';
                    $badgeClass = match($level) {
                        'Bronze' => 'bg-amber-100 text-amber-800',
                        'Silver' => 'bg-gray-100 text-gray-800',
                        'Gold' => 'bg-yellow-100 text-yellow-800',
                        'Platinum' => 'bg-purple-100 text-purple-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                    return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $badgeClass . '">' . $level . '</span>';
                })
                ->addColumn('action', function ($loyalty) {
                    return '<div class="flex space-x-2">
                        <a href="' . route('admin.loyalty.show', $loyalty->id) . '" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . route('admin.loyalty.edit', $loyalty->id) . '" class="text-green-600 hover:text-green-900">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['level', 'action'])
                ->make(true);
        }

        return view('admin.loyalty.index');
    }

    public function show($id)
    {
        $loyalty = Loyalty::with('user')->findOrFail($id);
        return view('admin.loyalty.show', compact('loyalty'));
    }

    public function edit($id)
    {
        $loyalty = Loyalty::with('user')->findOrFail($id);
        return view('admin.loyalty.edit', compact('loyalty'));
    }

    public function update(Request $request, $id)
    {
        $loyalty = Loyalty::findOrFail($id);
        $loyalty->update($request->only(['total_points', 'used_points', 'level']));
        
        return redirect()->route('admin.loyalty.index')->with('success', 'Loyalty data updated successfully');
    }

    // Export methods
    public function exportPdf(Request $request)
    {
        $query = Loyalty::with('user')->orderBy('updated_at', 'desc');
        return $this->exportPDF($request, $query, 'admin.exports.loyalty_pdf');
    }

    public function exportExcel(Request $request)
    {
        return $this->exportExcel($request, LoyaltyExport::class);
    }

    public function exportCsv(Request $request)
    {
        return $this->exportCSV($request, LoyaltyExport::class);
    }

    public function print(Request $request)
    {
        $query = Loyalty::with('user')->orderBy('updated_at', 'desc');
        return $this->printView($request, $query, 'admin.exports.loyalty_print');
    }

    protected function getModelName(): string
    {
        return 'loyalty';
    }

    protected function getExportTitle(): string
    {
        return 'Data Loyalty';
    }
}
