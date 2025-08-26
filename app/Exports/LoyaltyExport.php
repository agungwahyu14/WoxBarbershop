<?php

namespace App\Exports;

use App\Models\Loyalty;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LoyaltyExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month;
        $this->year = $year ?? date('Y');
    }

    public function query()
    {
        $query = Loyalty::with('user')->orderBy('created_at', 'desc');
        
        if ($this->month) {
            $query->whereYear('created_at', $this->year)
                  ->whereMonth('created_at', $this->month);
        }
        
        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Pelanggan',
            'Email Pelanggan',
            'Total Poin',
            'Poin Terpakai',
            'Sisa Poin',
            'Level Loyalitas',
            'Tanggal Update',
            'Status'
        ];
    }

    public function map($loyalty): array
    {
        return [
            $loyalty->id,
            $loyalty->user->name ?? '-',
            $loyalty->user->email ?? '-',
            $loyalty->total_points ?? 0,
            $loyalty->used_points ?? 0,
            ($loyalty->total_points ?? 0) - ($loyalty->used_points ?? 0),
            $loyalty->level ?? 'Bronze',
            Carbon::parse($loyalty->updated_at)->format('d/m/Y H:i'),
            $loyalty->deleted_at ? 'Inactive' : 'Active'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
