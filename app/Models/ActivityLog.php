<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'activity',
        'page_url',
        'ip_address',
        'user_agent',
        'activity_time',
        'data',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'deleted_by',
        'is_restored',
        'restored_at',
        'restored_by',
    ];

    public $timestamps = false;
}
