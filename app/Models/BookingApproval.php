<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingApproval extends Model
{
    protected $table = 'booking_approvals';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $fillable = [
        'booking_id',
        'user_id',
        'status',
        'order',
        'description',
        'approve_at',
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

    public function booking()
    {
        return $this->hasOne(Booking::class, 'id', 'booking_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
