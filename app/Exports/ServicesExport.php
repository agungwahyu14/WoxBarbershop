<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ServicesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = Service::orderBy('created_at', 'desc');
        
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
            'Nama Layanan',
            'Harga',
            'Durasi (menit)',
            'Deskripsi',
            'Tanggal Dibuat',
            'Status'
        ];
    }

    public function map($service): array
    {
        return [
            $service->id,
            $service->name,
            'Rp' . number_format($service->price, 0, ',', '.'),
            $service->duration . ' menit',
            strip_tags($service->description) ?: '-',
            Carbon::parse($service->created_at)->format('d/m/Y H:i'),
            $service->deleted_at ? 'Inactive' : 'Active'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
