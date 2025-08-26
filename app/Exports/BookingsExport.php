<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class BookingsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = Booking::with(['user', 'service'])->orderBy('created_at', 'desc');
        
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
            'Layanan',
            'Tanggal & Waktu',
            'Status',
            'Total Harga',
            'Catatan',
            'Tanggal Booking'
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->user->name ?? '-',
            $booking->user->email ?? '-',
            $booking->service->name ?? '-',
            Carbon::parse($booking->date_time)->format('d/m/Y H:i'),
            ucfirst($booking->status),
            'Rp' . number_format($booking->total_price, 0, ',', '.'),
            $booking->notes ?: '-',
            Carbon::parse($booking->created_at)->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
