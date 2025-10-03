<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $customers;
    protected $month;
    protected $year;

    public function __construct($customers, $month = null, $year = null)
    {
        $this->customers = $customers;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'No. Telepon',
            'Tanggal Daftar',
            'Total Booking',
            'Status Terakhir'
        ];
    }

    public function map($customer): array
    {
        static $no = 1;
        
        return [
            $no++,
            $customer->name,
            $customer->email,
            $customer->phone ?? 'N/A',
            Carbon::parse($customer->created_at)->format('d/m/Y'),
            $customer->bookings->count(),
            $customer->bookings->last()->status ?? 'Belum ada booking'
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
        
        return 'Laporan Pelanggan' . $period;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}