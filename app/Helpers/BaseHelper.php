<?php

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\User;
use App\Models\Vehicle;

function getListEmployee()
{
    return getEmployeeData(null, false);
}

function getListDriver()
{
    return getDriverData(null, false);
}

function generateCodeVehicle()
{
    $prefixs = getPrefixCode();
    $prefix = $prefixs->vehicle;
    $akhir = 1;
    $padLength = $prefixs->pad;
    $lastEntry = getLastRecordVehicle();
    if ($lastEntry) {
        $akhir = intval(str_replace($prefix, '', $lastEntry->code)) + 1;
    }
    return $prefix . str_pad($akhir, $padLength, '0', STR_PAD_LEFT);
}

function generateCodeEmployee()
{
    $prefixs = getPrefixCode();
    $prefix = $prefixs->employee;
    $akhir = 1;
    $padLength = $prefixs->pad;
    $lastEntry = getLastRecordEmployee();
    if ($lastEntry) {
        $akhir = intval(str_replace($prefix, '', $lastEntry->code)) + 1;
    }
    return $prefix . str_pad($akhir, $padLength, '0', STR_PAD_LEFT);
}

function generateCodeDriver()
{
    $prefixs = getPrefixCode();
    $prefix = $prefixs->driver;
    $akhir = 1;
    $padLength = $prefixs->pad;
    $lastEntry = getLastRecordDriver();
    if ($lastEntry) {
        $akhir = intval(str_replace($prefix, '', $lastEntry->code)) + 1;
    }
    return $prefix . str_pad($akhir, $padLength, '0', STR_PAD_LEFT);
}

function generateCodeBooking()
{
    $prefixs = getPrefixCode();
    $prefix = $prefixs->booking;
    $akhir = 1;
    $padLength = $prefixs->pad;
    $lastEntry = getLastRecordBooking();
    if ($lastEntry) {
        $akhir = intval(str_replace($prefix, '', $lastEntry->code)) + 1;
    }
    return $prefix . str_pad($akhir, $padLength, '0', STR_PAD_LEFT);
}

function getLastRecordVehicle()
{
    return getVehicleData(null, true);
}

function getLastRecordBooking()
{
    return getBookingData(null, true);
}

function getListVehicle()
{
    return getVehicleData(null, false);
}

function getLastRecordEmployee()
{
    return getEmployeeData(null, true);
}

function getLastRecordDriver()
{
    return getDriverData(null, true);
}

function getListApprover($exceptId = null)
{
    $whereRaw = null;
    if (!empty($exceptId)) {
        $whereRaw = "id <> $exceptId";
    }
    return getUserData(['group_id' => 2], false, $whereRaw);
}

function getVehicleData($where = null, $single = true)
{
    $model = new Vehicle();
    $columns = getColumns($model);

    $query = Vehicle::select($columns)->where($where)->orderBy('created_at', 'DESC');
    if ($single) {
        return $query->first();
    }
    return $query->get();
}

function getEmployeeById($id)
{
    return getEmployeeData(['id' => $id], true);
}

function getEmployeeData($where = null, $single = true)
{
    $model = new Employee();
    $columns = getColumns($model);

    $query = Employee::select($columns)->where($where)->orderBy('created_at', 'DESC');
    if ($single) {
        return $query->first();
    }
    return $query->get();
}

function getBookingApprovalPertama($bookingId)
{
    return getBookingApprovalData(['booking_id' => $bookingId, 'order' => '1'], true);
}

function getBookingApprovalById($id)
{
    return getBookingApprovalData(['id' => $id], true);
}

function getBookingApprovalData($where = null, $single = true)
{
    $model = new BookingApproval();
    $columns = getColumns($model);

    $query = BookingApproval::select($columns)->where($where)->orderBy('created_at', 'DESC');
    if ($single) {
        return $query->first();
    }
    return $query->get();
}

function getDriverData($where = null, $single = true)
{
    $model = new Driver();
    $columns = getColumns($model);

    $query = Driver::select($columns)->where($where)->orderBy('created_at', 'DESC');
    if ($single) {
        return $query->first();
    }
    return $query->get();
}

function getBookingData($where = null, $single = true)
{
    $model = new Booking();
    $columns = getColumns($model);

    $query = Booking::select($columns)->where($where)->orderBy('created_at', 'DESC');
    if ($single) {
        return $query->first();
    }
    return $query->get();
}

function getUserData($where = null, $single = true, $whereRaw = null)
{
    $model = new User();
    $columns = getColumns($model);
    $query = User::select($columns)->where($where)
        ->where(function ($query) use ($whereRaw) {
            if (!empty($whereRaw)) {
                $query->whereRaw($whereRaw);
            }
        })
        ->orderBy('created_at', 'DESC');
    if ($single) {
        return $query->first();
    }
    return $query->get();
}

function getPrefixCode()
{
    return (object) config('miftahululum.code');
}

function getApp()
{
    return (object) config('miftahululum.app');
}

function getAuthor()
{
    return (object) config('miftahululum.author');
}

function getClientIp()
{
    return request()->ip();
}

function getUserLoginId()
{
    $user = getUserLogin();
    if ($user) {
        return $user->id;
    }
    return;
}

function getUserAgent()
{
    return request()->header('user-agent');
}

function getUserLogin()
{
    return auth()->user();
}

function setActivityLog($activity, $data = null)
{
    return createActivityLog($activity, $data);
}

function createActivityLog($activity, $data = null, $userId = null)
{
    if (empty($userId)) {
        $userId = getUserLoginId();
    }
    $object = [
        'activity' => $activity,
        'user_id' => $userId,
        'created_by' => $userId,
        'page_url' => url()->current(),
        'ip_address' => getClientIp(),
        'user_agent' => getUserAgent(),
    ];

    if (!empty($data)) {
        $object['data'] = json_encode($data);
    }
    return ActivityLog::create($object);
}

function getColumns($model)
{
    return array_merge($model->getGuarded(), $model->getFillable());
}


function getTableClass()
{
    return getTableConfig()->class;
}

function getTableId()
{
    return getTableConfig()->id;
}

function getTablePageLength()
{
    return getTableConfig()->page_length;
}

function getTableConfig()
{
    return (object) config('miftahululum.table');
}

function createBooking($data)
{
    return Booking::create($data);
}

function updateBooking($id, $data)
{
    return Booking::where('id', $id)->update($data);
}

function createBookingApproval($data)
{
    return BookingApproval::create($data);
}

function insertBookingApproval($data)
{
    return BookingApproval::insert($data);
}

function getCountAllApprovalBooking($bookingId)
{
    return getCountBookingApproval($bookingId);
}

function getCountBookingApprovalApprove($bookingId)
{
    return getCountBookingApproval($bookingId, '1');
}

function getCountBookingApproval($bookingId, $status = null)
{
    return BookingApproval::where('booking_id', $bookingId)
        ->where(function ($query) use ($status) {
            if (isset($status)) {
                $query->where('status', $status);
            }
        })
        ->count();
}

function updateBookingApproval($id, $data)
{
    return BookingApproval::where('id', $id)->update($data);
}

function getListBookingByApprovalUserId($userId)
{
    return BookingApproval::where('user_id', $userId)->get();
}
