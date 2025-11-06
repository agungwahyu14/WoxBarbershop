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

class FinancialExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths, ShouldAutoSize
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
            'Tanggal Transaksi',
            'Waktu',
            'Order ID',
            'Nama Pelanggan',
            'Email Pelanggan',
            'Layanan',
            'Harga Layanan (Rp)',
            'Metode Pembayaran',
            'Status Transaksi',
            'Total Bayar (Rp)',
            'Fee Admin (Rp)',
            'Keterangan'
        ];
    }

    public function map($transaction): array
    {
        static $no = 1;
        
        // Status mapping untuk tampilan yang lebih baik
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'settlement' => 'Berhasil',
            'capture' => 'Berhasil',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kedaluwarsa',
            'failure' => 'Gagal'
        ];
        
        // Payment type mapping
        $paymentLabels = [
            'credit_card' => 'Kartu Kredit',
            'bank_transfer' => 'Transfer Bank',
            'echannel' => 'Mandiri Bill',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS'
        ];
        
        $servicePrice = $transaction->booking->service->price ?? 0;
        $adminFee = $transaction->gross_amount - $servicePrice;
        
        return [
            $no++,
            Carbon::parse($transaction->created_at)->format('d/m/Y'),
            Carbon::parse($transaction->created_at)->format('H:i:s'),
            $transaction->order_id,
            $transaction->booking->user->name ?? 'N/A',
            $transaction->booking->user->email ?? 'N/A',
            $transaction->booking->service->name ?? 'N/A',
            'Rp ' . number_format($servicePrice, 0, ',', '.'),
            $paymentLabels[$transaction->payment_type] ?? ucfirst($transaction->payment_type ?? 'N/A'),
            $statusLabels[$transaction->transaction_status] ?? ucfirst($transaction->transaction_status),
            'Rp ' . number_format($transaction->gross_amount, 0, ',', '.'),
            'Rp ' . number_format($adminFee, 0, ',', '.'),
            $transaction->fraud_status ? 'Status Fraud: ' . $transaction->fraud_status : '-'
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
            "H2:H{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            "K2:K{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            "L2:L{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            // Status column styling
            "J2:J{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 12,  // Tanggal
            'C' => 10,  // Waktu
            'D' => 20,  // Order ID
            'E' => 20,  // Nama
            'F' => 25,  // Email
            'G' => 25,  // Layanan
            'H' => 18,  // Harga Layanan
            'I' => 18,  // Metode Bayar
            'J' => 18,  // Status
            'K' => 18,  // Total Bayar
            'L' => 15,  // Fee Admin
            'M' => 25   // Keterangan
        ];
    }
}