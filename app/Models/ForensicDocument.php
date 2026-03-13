<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForensicDocument extends Model
{
    //
    protected $fillable = [
        'report_id',
        'file_name',
        'file_path'
    ];

    public function report()
    {
        return $this->belongsTo(ForensicReport::class, 'report_id');
    }
}
