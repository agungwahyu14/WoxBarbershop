<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class FinancialExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
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
            'Order ID',
            'Nama Pelanggan',
            'Layanan',
            'Metode Pembayaran',
            'Status',
            'Jumlah (Rp)'
        ];
    }

    public function map($transaction): array
    {
        static $no = 1;
        
        return [
            $no++,
            Carbon::parse($transaction->created_at)->format('d/m/Y H:i'),
            $transaction->order_id,
            $transaction->booking->user->name ?? 'N/A',
            $transaction->booking->service->name ?? 'N/A',
            $transaction->payment_type ?? 'N/A',
            ucfirst($transaction->transaction_status),
            number_format($transaction->gross_amount, 0, ',', '.')
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
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}