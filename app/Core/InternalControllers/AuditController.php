<?php

namespace App\Core\InternalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AuditController
 * 
 * Internal system controller responsible for recording made to 
 * Eloquent models within the audit table ('audits')
 * 
 * ⚠️ This controller isn't publicy displayed or on routes.
 * Its use is internal and programmatic
 * 
 * Every audit record includes:
 * - Affected model
 * - Old and new values (just modified fields)
 * - User whose mades the change (ID, username and rol)
 * - Ip and user agent 
 * - Action performed ('create','update','delete')
 */

class AuditController extends Controller
{
    public function log(
        Model $model,
        int $userId,
        string $username,
        string $userRole,
        string $action
    ): void {
        $dirty = $model->getDirty();

        // Si no hay cambios y no es una creación, salimos
        // if (empty($dirty)) {
        //     return;
        // }

        $oldValues = null;
        $newValues = null;


        if ($action === 'CREATED') {
            $newValues = $model->getAttributes();
        } elseif ($action === 'UPDATED') {
            $changes = $model->getChanges();
            if (empty($changes)) {
                return;
            }
            $newValues = $changes;
            $oldValues = array_intersect_key($model->getOriginal(), $changes);
        } elseif ($action === 'DELETED') {
            $oldValues = $model->getAttributes();
        }

        $oldValues = $this->sanitizeValues($oldValues);
        $newValues = $this->sanitizeValues($newValues);

        DB::table('audits')->insert([
            'auditable_type'   => get_class($model),
            'auditable_id'     => $model->getKey(),
            'auditable_action' => $action,
            'old_value'        => $oldValues ? json_encode($oldValues) : null,
            'new_value'        => $newValues ? json_encode($newValues) : null,
            'user_id'          => $userId,
            'username'         => $username,
            'user_rol'        => $userRole,
            'ip_address'       => request()->ip(),
            'user_agent'       => request()->userAgent(),
            'performed_at'     => now(),
        ]);
    }

    private function sanitizeValues(?array $values): ?array
    {
        if (!$values) {
            return $values;
        }

        $redactKeys = [
            'password',
            'password_confirmation',
            'remember_token',
            'api_token',
            'access_token',
            'refresh_token',
            'token',
            'secret',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ];

        foreach ($values as $key => $value) {
            $k = strtolower((string) $key);
            if (in_array($k, $redactKeys, true)) {
                $values[$key] = '[REDACTED]';
            }
        }

        return $values;
    }
}
