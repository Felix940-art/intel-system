<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoInt extends Model
{
    use HasFactory;

    protected $table = 'geo_ints'; // change if your table name is different

    protected $fillable = [
        'mission_datetime',
        'uav',
        'home_point_mgrs',
        'latitude',
        'longitude',
        'threat_confronted',
        'classification',
        'document_path'
    ];

    protected $casts = [
        'mission_datetime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
