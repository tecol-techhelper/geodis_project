<?php

namespace App\Core\InternalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        string $action): void
    {
        // Getting the columns that have been changed and their values
        $dirty = $model->getDirty();

        // If there are no changes, we don't need to log anything
        if (empty($dirty)) {
            return;
        }

        // Getting just the original values of the changed columns
        $original = array_intersect_key($model->getOriginal(), $dirty);

        // Sortting the arrays by key to make the comparison easier
        ksort($original);
        ksort($dirty);

        // Inserting the audit values into the database
        DB::table('audits')->insert([
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'auditable_action' => $action,
            'old_values' => json_encode($original),
            'new_values' => json_encode($dirty),
            'user_id' => $userId,
            'username' => $username,
            'user_role' => $userRole,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => now(),
        ]);
    }
}
