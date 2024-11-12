<?php

namespace App\Http\Controllers;

use App\DataTables\BookingsDataTable;
use Exception;
use Illuminate\Http\Request;

class BookingApprovalController extends Controller
{
    protected $directory = 'bookingapproval';

    public function index()
    {
        $datatable = new BookingsDataTable();
        $this->data['title'] = 'Booking';
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
                ];
                createBookingApproval($approval);
            }
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
}
