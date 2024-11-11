<?php

use App\Models\ActivityLog;

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
