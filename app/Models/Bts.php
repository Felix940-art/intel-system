<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bts extends Model
{
    protected $table = 'bts';

    protected $fillable = [

        'name',
        'mgrs_location',
        'network',
        'network_mode',
        'lac',
        'cid',
        'neighbor_cid',
        'barangay',
        'municipality',
        'province'

    ];
}
