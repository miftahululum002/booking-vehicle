<?php

namespace App\Http\Controllers;

use App\DataTables\ReportBookingsDataTable;
use Exception;
use Illuminate\Http\Request;

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

    public function create()
    {
        $this->data['employees'] = getListEmployee();
        $this->data['drivers'] = getListDriver();
        $this->data['title'] = 'Tambah Booking';
        $this->data['approvers'] = getListApprover();
        $this->data['vehicles'] = getListVehicle();
        return $this->render('create');
    }

    public function getApprover(Request $request)
    {
        $input = $request->validate([
            'user_id' => 'required',
        ]);
        $result = getListApprover($input['user_id']);
        return response()->json([
            'message' => 'Success',
            'code' => 200,
            'data' => $result,
        ], 200);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'employee_id'   => 'required',
            'vehicle_id'    => 'required',
            'driver_id'     => 'required',
            'user_id'       => 'required',
            'date'          => 'required',
            'necessary'     => 'required',
        ]);
        $booking = $input;
        $approvals = $input['user_id'];
        unset($booking['user_id']);
        $booking['code'] = generateCodeBooking();
        try {
            $store = createBooking($booking);
            $bookingId = $store->id;
            foreach ($approvals as $key => $value) {
                $approval = [
                    'booking_id' => $bookingId,
                    'user_id' => $value,
                    'status' => '0',
                    'order' => ($key + 1)
                ];
                createBookingApproval($approval);
            }
            setActivityLog('Create booking', $input);
            return response()->json([
                'code'      => 200,
                'message'   => 'Proses berhasil',
                'data'      => null,
                'error'     => null,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code'      => 500,
                'message'   => 'Proses gagal',
                'data'      => null,
                'error'     => $e->getMessage(),
            ], 500);
        }
    }

    public function setDone(Request $request)
    {
        $input = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);
        $bookingId = $input['booking_id'];
        $userId = getUserLoginId();
        try {
            updateBooking($input['booking_id'], [
                'is_done' => '1',
                'done_at' => date('Y-m-d H:i:s'),
                'done_by' => $userId
            ]);
            setActivityLog('Set done booking:' . $bookingId, $input);
            return response()->json([
                'code' => 200,
                'message' => 'Proses berhasil dilakukan',
                'error' => null,
                'data' => null,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Proses gagal dilakukan',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
