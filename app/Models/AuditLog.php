<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'module',
        'action',
        'model',
        'record_id',
        'ip_address',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
