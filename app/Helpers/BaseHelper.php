<?php

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\Vehicle;


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

function getLastRecordVehicle()
{
    return getVehicleData(null, true);
}

function getLastRecordEmployee()
{
    return getEmployeeData(null, true);
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
