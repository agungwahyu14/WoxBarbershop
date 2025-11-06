<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths, ShouldAutoSize
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
            'Email Pelanggan',
            'No. Telepon',
            'Layanan',
            'Durasi (Menit)',
            'Status Booking',
            'Total Harga (Rp)',
            'Metode Pembayaran',
            'Catatan'
        ];
    }

    public function map($booking): array
    {
        static $no = 1;
        
        // Status mapping untuk tampilan yang lebih baik
        $statusLabels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];
        
        return [
            $no++,
            Carbon::parse($booking->date_time)->format('d/m/Y'),
            Carbon::parse($booking->date_time)->format('H:i'),
            $booking->user->name ?? 'N/A',
            $booking->user->email ?? 'N/A',
            $booking->user->phone ?? 'N/A',
            $booking->service->name ?? 'N/A',
            $booking->service->duration ?? 'N/A',
            $statusLabels[$booking->status] ?? ucfirst($booking->status),
            'Rp ' . number_format($booking->total_price, 0, ',', '.'),
            $booking->transaction->payment_type ?? 'Belum Dibayar',
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
            "J2:J{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ],
            // Status column styling
            "I2:I{$highestRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 10,  // Waktu
            'D' => 20,  // Nama
            'E' => 25,  // Email
            'F' => 15,  // Telepon
            'G' => 25,  // Layanan
            'H' => 10,  // Durasi
            'I' => 20,  // Status
            'J' => 18,  // Harga
            'K' => 18,  // Metode Bayar
            'L' => 30   // Catatan
        ];
    }
}