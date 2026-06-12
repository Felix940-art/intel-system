<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AuditLog;

class SreEvent extends Model
{
    protected $fillable = [
        'sre_selector_id',
        'observed_at',
        'imei',
        'imsi',
        'lac',
        'cid',
        'code_name',
        'threat_group',
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


    protected static function booted()
    {
        static::created(function ($model) {
            AuditLog::create([
                'user_id'    => auth()->id(),
                'role'       => auth()->user()->role ?? null,
                'module'     => 'SRE',
                'action'     => 'CREATE',
                'model'      => 'SreEvent',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Created SRE ID: ' . $model->id,
            ]);
        });

        static::updated(function ($model) {
            AuditLog::create([
                'user_id'    => auth()->id(),
                'role'       => auth()->user()->role ?? null,
                'module'     => 'SRE',
                'action'     => 'UPDATE',
                'model'      => 'SreEvent',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Updated SRE ID: ' . $model->id,
            ]);
        });

        static::deleted(function ($model) {
            AuditLog::create([
                'user_id'    => auth()->id(),
                'role'       => auth()->user()->role ?? null,
                'module'     => 'SRE',
                'action'     => 'DELETE',
                'model'      => 'SreEvent',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Deleted SRE ID: ' . $model->id,
            ]);
        });
    }
}
