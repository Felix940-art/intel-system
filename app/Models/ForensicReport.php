<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AuditLog;

class ForensicReport extends Model
{
    use HasFactory;

    protected $table = 'forensic_reports';

    protected $fillable = [
        'extraction_date',
        'location',
        'equipment_type',
        'remarks',
        'reason_not_extracted',
        'examiner_name'
    ];

    protected $casts = [
        'extraction_date' => 'date',
    ];

    public function documents()
    {
        return $this->hasMany(ForensicDocument::class, 'report_id');
    }


    protected static function booted()
    {
        static::created(function ($model) {
            AuditLog::create([
                'user_id'    => auth()->id(),
                'role'       => auth()->user()->role ?? null,
                'module'     => 'FORENSICS',
                'action'     => 'CREATE',
                'model'      => 'ForensicReport',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Created FORENSICS ID: ' . $model->id,
            ]);
        });

        static::updated(function ($model) {
            AuditLog::create([
                'user_id'    => auth()->id(),
                'role'       => auth()->user()->role ?? null,
                'module'     => 'FORENSICS',
                'action'     => 'UPDATE',
                'model'      => 'ForensicReport',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Updated FORENSICS ID: ' . $model->id,
            ]);
        });

        static::deleted(function ($model) {
            AuditLog::create([
                'user_id'    => auth()->id(),
                'role'       => auth()->user()->role ?? null,
                'module'     => 'FORENSICS',
                'action'     => 'DELETE',
                'model'      => 'ForensicReport',
                'record_id'  => $model->id,
                'ip_address' => request()->ip(),
                'description' => 'Deleted FORENSICS ID: ' . $model->id,
            ]);
        });
    }
}
