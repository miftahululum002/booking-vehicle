<?php

namespace App\Exports;

use App\Models\Booking as Model;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BookingReportExport implements FromQuery, WithMapping, WithTitle, WithHeadings
{
    private $_startDate;
    private $_endDate;
    private $_number = 1;
    private $_title = null;

    public function __construct($startDate, $endDate, $title = null)
    {
        $startDate = !empty($startDate) ? $startDate : date('Y-m-d');
        $endDate = !empty($endDate) ? $endDate : date('Y-m-d');
        $this->_startDate = $startDate;
        $this->_endDate = $endDate;
        $this->_title = $title;
    }

    public function query()
    {
        $startDate = $this->_startDate;
        $endDate = $this->_endDate;
        return Model::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })->orderBy('created_at', 'ASC');
    }

    public function map($row): array
    {
        $approval = $row->approval;
        $approval1 = null;
        $approval2 = null;
        foreach ($approval as $key => $value) {
            $user = $value->user;
            if ($value->order == 1) {
                $approval1 = ['name' => $user->name, 'status' => $value->status == 1 ? 'Disetujui' : 'Belum/Tidak Disetujui', 'approve_at' => $value->approve_at];
            } else {
                $approval2 = ['name' => $user->name, 'status' => $value->status == 1 ? 'Disetujui' : 'Belum/Tidak Disetujui', 'approve_at' => $value->approve_at];
            }
        }
        return [
            $this->_number++,
            $row->code,
            $row->employee->code . ' - ' . $row->employee->name,
            $row->vehicle->code . ' - ' . $row->vehicle->name,
            $row->driver->code . ' - ' . $row->driver->name,
            $row->date,
            $row->necessary,
            $row->status,
            $approval1['name'],
            $approval1['status'],
            $approval1['approve_at'],
            $approval2['name'],
            $approval2['status'],
            $approval2['approve_at'],
            $row->is_done == '1' ? 'Ya' : 'Belum',
            $row->done_at,
            $row->created_at,
        ];
    }
    public function title(): string
    {
        return $this->_title;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Pegawai',
            'Kendaraan',
            'Sopir',
            'Tanggal',
            'Keperluan',
            'Status',
            'Approval 1',
            'Status Approval 1',
            'Waktu Approve 1',
            'Approval 2',
            'Status Approval 2',
            'Waktu Approve 2',
            'Apakah Selesai',
            'Waktu Selesai',
            'Tanggal Entri',
        ];
    }
}
