<?php

namespace App\Http\Controllers;

use App\DataTables\ReportBookingsDataTable;
use App\Exports\BookingReportExport;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected $directory = 'report';

    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $datatable = new ReportBookingsDataTable($startDate, $endDate);
        $this->data['title'] = 'Laporan';
        return $this->renderDatatable('index', $datatable);
    }

    public function export(Request $request)
    {
        $input = $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);
        $title = 'laporan_booking-' . $input['start'] . '_' . $input['end'] . '_' . now() . '.xlsx';
        $titleSheet = $input['start'] . '_s.d_' . $input['end'];
        return Excel::download(new BookingReportExport($input['start'], $input['end'], $titleSheet), $title);
    }
}
