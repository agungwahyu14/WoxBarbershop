<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Response;

trait ExportTrait
{
    /**
     * Export data to PDF
     */
    public function exportPDF(Request $request, $data, $viewName, $filename = null)
    {
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        
        if ($month) {
            $data = $this->filterByMonth($data, $month, $year);
            $monthName = Carbon::create($year, $month)->translatedFormat('F Y');
            $filename = $filename ?? $this->getModelName() . '_' . $monthName . '.pdf';
        } else {
            $filename = $filename ?? $this->getModelName() . '_all_data.pdf';
            $monthName = 'Semua Data';
        }

        $pdf = Pdf::loadView($viewName, [
            'data' => $data,
            'title' => $this->getExportTitle(),
            'monthName' => $monthName,
            'exportDate' => now()->format('d F Y H:i'),
        ]);

        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download($filename);
    }

    /**
     * Export data to Excel
     */
    public function exportExcel(Request $request, $exportClass, $filename = null)
    {
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        
        if ($month) {
            $monthName = Carbon::create($year, $month)->translatedFormat('F_Y');
            $filename = $filename ?? $this->getModelName() . '_' . $monthName . '.xlsx';
        } else {
            $filename = $filename ?? $this->getModelName() . '_all_data.xlsx';
        }

        return Excel::download(new $exportClass($month, $year), $filename);
    }

    /**
     * Export data to CSV
     */
    public function exportCSV(Request $request, $exportClass, $filename = null)
    {
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        
        if ($month) {
            $monthName = Carbon::create($year, $month)->translatedFormat('F_Y');
            $filename = $filename ?? $this->getModelName() . '_' . $monthName . '.csv';
        } else {
            $filename = $filename ?? $this->getModelName() . '_all_data.csv';
        }

        return Excel::download(new $exportClass($month, $year), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Get printable view
     */
    public function printView(Request $request, $data, $viewName)
    {
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        
        if ($month) {
            $data = $this->filterByMonth($data, $month, $year);
            $monthName = Carbon::create($year, $month)->translatedFormat('F Y');
        } else {
            $monthName = 'Semua Data';
        }

        return view($viewName, [
            'data' => $data,
            'title' => $this->getExportTitle(),
            'monthName' => $monthName,
            'exportDate' => now()->format('d F Y H:i'),
            'isPrint' => true,
        ]);
    }

    /**
     * Filter data by month and year
     */
    protected function filterByMonth($query, $month, $year)
    {
        return $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
    }

    /**
     * Get model name for filename
     */
    abstract protected function getModelName(): string;

    /**
     * Get export title
     */
    abstract protected function getExportTitle(): string;
}
