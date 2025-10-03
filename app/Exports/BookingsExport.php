<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $bookings;
    protected $month;
    protected $year;

    public function __construct($bookings, $month = null, $year = null)
    {
        $this->bookings = $bookings;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Booking',
            'Waktu',
            'Nama Pelanggan',
            'Layanan',
            'Status',
            'Total Harga (Rp)',
            'Catatan'
        ];
    }

    public function map($booking): array
    {
        static $no = 1;
        
        return [
            $no++,
            Carbon::parse($booking->date_time)->format('d/m/Y'),
            Carbon::parse($booking->date_time)->format('H:i'),
            $booking->user->name ?? 'N/A',
            $booking->service->name ?? 'N/A',
            ucfirst($booking->status),
            number_format($booking->total_price, 0, ',', '.'),
            $booking->notes ?? '-'
        ];
    }

    public function title(): string
    {
        $period = '';
        if ($this->month && $this->year) {
            $period = ' - ' . Carbon::create($this->year, $this->month)->format('F Y');
        } elseif ($this->year) {
            $period = ' - ' . $this->year;
        }
        
        return 'Laporan Booking' . $period;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}