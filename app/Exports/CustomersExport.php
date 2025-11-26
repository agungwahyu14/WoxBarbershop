<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths, WithEvents
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
            'Telepon',
            'Tgl Daftar',
            'Status',
            'Total Booking',
            'Selesai',
            'Total (Rp)',
            'Loyalty'
        ];
    }

    public function map($customer): array
    {
        static $no = 1;
        
        // Hitung statistik customer
        $totalBookings = $customer->bookings->count();
        $completedBookings = $customer->bookings->where('status', 'completed')->count();
        $totalSpent = $customer->bookings->where('status', 'completed')->sum('total_price');
        $loyalty = $customer->loyalty;
        
        // Status akun
        $accountStatus = $customer->email_verified_at ? 'Aktif' : 'Pending';
        
        // Status loyalty berdasarkan total pengeluaran
        $loyaltyStatus = 'Bronze';
        if ($totalSpent >= 1000000) {
            $loyaltyStatus = 'Gold';
        } elseif ($totalSpent >= 500000) {
            $loyaltyStatus = 'Silver';
        }
        
        return [
            $no++,
            $this->truncateText($customer->name, 25),
            $this->truncateText($customer->email, 25),
            $customer->phone ?? 'N/A',
            Carbon::parse($customer->created_at)->format('d/m/Y'),
            $accountStatus,
            $totalBookings,
            $completedBookings,
            number_format($totalSpent, 0, ',', '.'),
            $loyaltyStatus . ' (' . ($loyalty->points ?? 0) . ')'
        ];
    }
    
    private function truncateText($text, $length)
    {
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
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
            // Status columns alignment
            "F2:F{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            "J2:J{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,   // No
            'B' => 20,  // Nama
            'C' => 25,  // Email
            'D' => 12,  // Telepon
            'E' => 11,  // Tanggal Daftar
            'F' => 8,   // Status Akun
            'G' => 8,   // Total Booking
            'H' => 8,   // Booking Selesai
            'I' => 15,  // Total Pengeluaran
            'J' => 15   // Loyalty dengan poin
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set page orientation to landscape
                $event->sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);
                
                // Set margins
                $event->sheet->getPageMargins()
                    ->setTop(0.5)
                    ->setRight(0.5)
                    ->setBottom(0.5)
                    ->setLeft(0.5);
                
                // Enable text wrapping for all cells
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $event->sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(true);
            }
        ];
    }
}