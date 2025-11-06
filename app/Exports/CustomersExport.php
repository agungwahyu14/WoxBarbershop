<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths, ShouldAutoSize
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
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Tanggal Daftar',
            'Status Akun',
            'Total Booking',
            'Booking Selesai',
            'Total Pengeluaran (Rp)',
            'Booking Terakhir',
            'Status Loyalty',
            'Poin Loyalty'
        ];
    }

    public function map($customer): array
    {
        static $no = 1;
        
        // Hitung statistik customer
        $totalBookings = $customer->bookings->count();
        $completedBookings = $customer->bookings->where('status', 'completed')->count();
        $totalSpent = $customer->bookings->where('status', 'completed')->sum('total_price');
        $lastBooking = $customer->bookings->last();
        $loyalty = $customer->loyalty;
        
        // Status akun
        $accountStatus = $customer->email_verified_at ? 'Aktif' : 'Belum Verifikasi';
        
        // Status loyalty berdasarkan total pengeluaran
        $loyaltyStatus = 'Bronze';
        if ($totalSpent >= 1000000) {
            $loyaltyStatus = 'Gold';
        } elseif ($totalSpent >= 500000) {
            $loyaltyStatus = 'Silver';
        }
        
        return [
            $no++,
            $customer->name,
            $customer->email,
            $customer->phone ?? 'N/A',
            Carbon::parse($customer->created_at)->format('d/m/Y'),
            $accountStatus,
            $totalBookings,
            $completedBookings,
            'Rp ' . number_format($totalSpent, 0, ',', '.'),
            $lastBooking ? Carbon::parse($lastBooking->date_time)->format('d/m/Y') : 'Belum ada',
            $loyaltyStatus,
            $loyalty->points ?? 0
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
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        return [
            // Header styling
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7B1FA2']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
            // Data styling
            "A2:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Number column alignment
            "A2:A{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Numeric columns alignment
            "G2:G{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            "H2:H{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            "I2:I{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            "L2:L{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Status columns alignment
            "F2:F{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            "K2:K{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 25,  // Nama
            'C' => 30,  // Email
            'D' => 15,  // Telepon
            'E' => 15,  // Tanggal Daftar
            'F' => 18,  // Status Akun
            'G' => 12,  // Total Booking
            'H' => 15,  // Booking Selesai
            'I' => 20,  // Total Pengeluaran
            'J' => 15,  // Booking Terakhir
            'K' => 15,  // Status Loyalty
            'L' => 12   // Poin Loyalty
        ];
    }
}