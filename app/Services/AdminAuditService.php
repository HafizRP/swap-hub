<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AdminAuditService
{
    /**
     * Record an administrative audit log entry.
     *
     * @param  array<string, mixed>  $details
     */
    public function log(
        User $admin,
        string $action,
        ?Model $target = null,
        array $details = [],
        ?string $ipAddress = null
    ): AdminAuditLog {
        return AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'target_type' => $target ? $target->getMorphClass() : null,
            'target_id' => $target ? (int) $target->getKey() : null,
            'details' => empty($details) ? null : $details,
            'ip_address' => $ipAddress ?? request()?->ip(),
        ]);
    }
}
