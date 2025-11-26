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

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths, WithEvents
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
            'Tgl Booking',
            'Waktu',
            'Nama Pelanggan',
            'Email',
            'Telepon',
            'Layanan',
            'Status',
            'Total (Rp)',
            'Pembayaran',
            'Catatan'
        ];
    }

    public function map($booking): array
    {
        static $no = 1;
        
        // Status mapping untuk tampilan yang lebih baik
        $statusLabels = [
            'pending' => 'Pending',
            'confirmed' => 'Konfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Batal'
        ];
        
        return [
            $no++,
            Carbon::parse($booking->date_time)->format('d/m/Y'),
            Carbon::parse($booking->date_time)->format('H:i'),
            $this->truncateText($booking->user->name ?? 'N/A', 20),
            $this->truncateText($booking->user->email ?? 'N/A', 25),
            $booking->user->phone ?? 'N/A',
            $this->truncateText($booking->service->name ?? 'N/A', 20),
            $statusLabels[$booking->status] ?? ucfirst($booking->status),
            'Rp ' . number_format($booking->total_price, 0, ',', '.'),
            $this->truncateText($booking->transaction->payment_type ?? 'Belum Bayar', 15),
            $this->truncateText($booking->notes ?? '-', 25)
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
        
        return 'Laporan Booking' . $period;
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
                    'startColor' => ['rgb' => '4472C4']
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
            // Price column alignment
            "I2:I{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            // Status column styling
            "H2:H{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,   // No
            'B' => 11,  // Tanggal
            'C' => 7,   // Waktu
            'D' => 18,  // Nama
            'E' => 22,  // Email
            'F' => 12,  // Telepon
            'G' => 18,  // Layanan
            'H' => 12,  // Status
            'I' => 15,  // Harga
            'J' => 12,  // Metode Bayar
            'K' => 20   // Catatan
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