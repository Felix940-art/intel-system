<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForensicReport extends Model
{
    use HasFactory;

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
}
