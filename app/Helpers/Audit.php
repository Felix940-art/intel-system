<?php

namespace App\Helpers;

use App\Models\AuditLog;

class Audit
{
    public static function log(
        string $module,
        string $action,
        ?string $model = null,
        ?int $recordId = null,
        ?string $description = null
    ): void {
        AuditLog::create([
            'user_id' => auth()->id(),
            'role'    => auth()->user()?->role,
            'module'     => $module,
            'action'     => $action,
            'model'      => $model,
            'record_id'  => $recordId,
            'ip_address' => request()->ip(),
            'description' => $description,
        ]);
    }
}
