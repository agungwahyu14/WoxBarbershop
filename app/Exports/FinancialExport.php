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

class FinancialExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $transactions;
    protected $month;
    protected $year;

    public function __construct($transactions, $month = null, $year = null)
    {
        $this->transactions = $transactions;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Order ID',
            'Nama Pelanggan',
            'Layanan',
            'Harga (Rp)',
            'Pembayaran',
            'Status',
            'Total (Rp)',
            'Fee (Rp)'
        ];
    }

    public function map($transaction): array
    {
        static $no = 1;
        
        // Status mapping untuk tampilan yang lebih baik
        $statusLabels = [
            'pending' => 'Pending',
            'settlement' => 'Berhasil',
            'capture' => 'Berhasil',
            'deny' => 'Ditolak',
            'cancel' => 'Batal',
            'expire' => 'Expired',
            'failure' => 'Gagal'
        ];
        
        // Payment type mapping
        $paymentLabels = [
            'credit_card' => 'Kredit',
            'bank_transfer' => 'Transfer',
            'echannel' => 'Mandiri',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS'
        ];
        
        $servicePrice = $transaction->booking->service->price ?? 0;
        $adminFee = $transaction->gross_amount - $servicePrice;
        
        return [
            $no++,
            Carbon::parse($transaction->created_at)->format('d/m/Y H:i'),
            $this->truncateText($transaction->order_id, 15),
            $this->truncateText($transaction->booking->user->name ?? 'N/A', 20),
            $this->truncateText($transaction->booking->service->name ?? 'N/A', 20),
            number_format($servicePrice, 0, ',', '.'),
            $paymentLabels[$transaction->payment_type] ?? $this->truncateText($transaction->payment_type ?? 'N/A', 10),
            $statusLabels[$transaction->transaction_status] ?? ucfirst($transaction->transaction_status),
            number_format($transaction->gross_amount, 0, ',', '.'),
            number_format($adminFee, 0, ',', '.')
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
        
        return 'Laporan Keuangan' . $period;
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
                    'startColor' => ['rgb' => '2E7D32']
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
            // Price columns alignment
            "F2:F{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            "I2:I{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            "J2:J{$highestRow}" => [
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
            'B' => 14,  // Tanggal
            'C' => 13,  // Order ID
            'D' => 18,  // Nama
            'E' => 18,  // Layanan
            'F' => 12,  // Harga
            'G' => 12,  // Pembayaran
            'H' => 10,  // Status
            'I' => 12,  // Total
            'J' => 10   // Fee
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