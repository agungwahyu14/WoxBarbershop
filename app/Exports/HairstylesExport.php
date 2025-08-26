<?php

namespace App\Exports;

use App\Models\Hairstyle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class HairstylesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = Hairstyle::orderBy('created_at', 'desc');
        
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
            'Nama Gaya Rambut',
            'Deskripsi',
            'Jenis Rambut',
            'Tingkat Kesulitan',
            'Tanggal Dibuat',
            'Status'
        ];
    }

    public function map($hairstyle): array
    {
        return [
            $hairstyle->id,
            $hairstyle->name,
            strip_tags($hairstyle->description) ?: '-',
            $hairstyle->hair_type ?: '-',
            $hairstyle->difficulty_level ?: '-',
            Carbon::parse($hairstyle->created_at)->format('d/m/Y H:i'),
            $hairstyle->deleted_at ? 'Inactive' : 'Active'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
