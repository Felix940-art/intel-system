<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SreEvent extends Model
{
    protected $fillable = [
        'sre_selector_id',
        'observed_at',
        'imei',
        'imsi',
        'lac',
        'cid',
        'bts_location',
        'bts_lat',
        'bts_lng'
    ];

    protected $casts = [
        'observed_at' => 'datetime'
    ];

    public function selector()
    {
        return $this->belongsTo(SreSelector::class, 'sre_selector_id');
    }
}
