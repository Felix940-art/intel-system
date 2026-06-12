<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BtsSite extends Model
{
    protected $fillable = [
        'name',
        'mgrs_location',
        'network',
        'network_mode',
        'lac',
        'cid',
        'neighboring_cid',
        'barangay',
        'municipality',
        'province'
    ];
}
