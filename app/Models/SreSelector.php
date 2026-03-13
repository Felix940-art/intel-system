<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SreSelector extends Model
{
    protected $fillable = [
        'user_id',
        'selector_type',
        'selector_value',
        'threat_group',
        'code_name',
        'remarks',
        'is_active'
    ];

    public function events()
    {
        return $this->hasMany(SreEvent::class);
    }
}
