<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'user_groups';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
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
