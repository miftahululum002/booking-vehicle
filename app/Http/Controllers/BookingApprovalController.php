<?php

namespace App\Http\Controllers;

use App\DataTables\ApprovalBookingsDataTable;
use App\DataTables\BookingsDataTable;
use Exception;
use Illuminate\Http\Request;

class BookingApprovalController extends Controller
{
    protected $directory = 'bookingapproval';

    public function index()
    {
        $userId = getUserLoginId();
        $myApprovalBooking = getBookingIdByApprovalUserId($userId);
        $datatable = new ApprovalBookingsDataTable($userId, $myApprovalBooking);
        $this->data['title'] = 'Persetujuan Booking';
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

    public function approve(Request $request)
    {
        $input = $request->validate([
            'booking_id'   => 'required',
            'approve_id'    => 'required|exists:booking_approvals,id',
        ]);
        $userId = getUserLoginId();
        $bookingId = $input['booking_id'];
        $approveId = $input['approve_id'];
        $approveData = getBookingApprovalById($approveId);
        $order = $approveData->order;
        if ($order != 1) {
            $cekFirst = getBookingApprovalPertama($bookingId);
            if ($cekFirst->status != '1') {
                $code = '401';
                return response()->json([
                    'code' => $code,
                    'message' => 'Approver sebelumnya belum menyetujui',
                    'error' => 'Approver sebelumnya belum menyetujui',
                ], $code);
            }
        }
        $jumlahApproval = getCountAllApprovalBooking($bookingId);
        try {
            updateBookingApproval($approveId, ['status' => '1', 'updated_by' => $userId, 'approve_at' => date('Y-m-d H:i:s')]);
            $cek = getCountBookingApprovalApprove($bookingId);
            if ($cek == $jumlahApproval) {
                updateBooking($bookingId, ['status' => 'APPROVED', 'updated_by' => $userId]);
            } else {
                updateBooking($bookingId, ['status' => 'APPROVAL', 'updated_by' => $userId]);
            }
            setActivityLog('Approve booking:' . $bookingId, ['booking_id' => $bookingId, 'approval_id' => $approveId, 'status' => '1']);
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
