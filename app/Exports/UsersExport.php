<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = User::with('roles')->orderBy('created_at', 'desc');
        
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
            'Nama',
            'Email',
            'No. Telepon',
            'Role',
            'Email Verified',
            'Tanggal Daftar',
            'Status'
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->no_telepon ?? '-',
            $user->roles->pluck('name')->join(', ') ?: 'pelanggan',
            $user->email_verified_at ? 'Verified' : 'Not Verified',
            Carbon::parse($user->created_at)->format('d/m/Y H:i'),
            $user->deleted_at ? 'Inactive' : 'Active'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
