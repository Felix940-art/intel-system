<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AuditLog;

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

    protected static function booted()
    {
        static::created(function ($model) {

            $user = auth()->user();

            AuditLog::create([
                'user_id'    => $user?->id,
                'role'       => $user?->role,
                'module'     => 'GEOINT',
                'action'     => 'CREATE',
                'model'      => 'GeoInt',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Created GEOINT ID: ' . $model->id,
            ]);
        });

        static::updated(function ($model) {

            $user = auth()->user();

            AuditLog::create([
                'user_id'    => $user?->id,
                'role'       => $user?->role,
                'module'     => 'GEOINT',
                'action'     => 'UPDATE',
                'model'      => 'GeoInt',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Updated GEOINT ID: ' . $model->id,
            ]);
        });

        static::deleted(function ($model) {

            $user = auth()->user();

            AuditLog::create([
                'user_id'    => $user?->id,
                'role'       => $user?->role,
                'module'     => 'GEOINT',
                'action'     => 'DELETE',
                'model'      => 'GeoInt',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Deleted GEOINT ID: ' . $model->id,
            ]);
        });
    }
}
