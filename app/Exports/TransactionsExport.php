<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = Transaction::with(['booking.user', 'booking.service'])->orderBy('created_at', 'desc');
        
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
            'Order ID',
            'Pelanggan',
            'Layanan',
            'Jumlah',
            'Status Transaksi',
            'Metode Pembayaran',
            'Tanggal Transaksi',
            'Tanggal Dibuat'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->order_id,
            $transaction->booking->user->name ?? '-',
            $transaction->booking->service->name ?? '-',
            'Rp' . number_format($transaction->gross_amount, 0, ',', '.'),
            ucfirst($transaction->transaction_status),
            $transaction->payment_type ?? '-',
            $transaction->transaction_time ? Carbon::parse($transaction->transaction_time)->format('d/m/Y H:i') : '-',
            Carbon::parse($transaction->created_at)->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
