<?php

namespace App\Providers;

use App\Core\InternalControllers\AuditController;
use Illuminate\Auth\SessionGuard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $slowThresholdMs = (int) env('SLOW_QUERY_MS', 200);
        DB::listen(function ($query) use ($slowThresholdMs): void {
            if ($query->time < $slowThresholdMs) {
                return;
            }

            Log::warning('Slow query detected', [
                'time_ms' => $query->time,
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'connection' => $query->connectionName,
            ]);
        });

        Auth::resolved(function ($auth): void {
            $guard = $auth->guard();
            if ($guard instanceof SessionGuard) {
                $minutes = (int) config('auth.remember_duration', 60 * 24 * 7);
                $guard->setRememberDuration($minutes);
            }
        });

        Event::listen([
            'eloquent.created: *',
            'eloquent.updated: *',
            'eloquent.deleted: *',
        ], function (string $eventName, array $data): void {
            $model = $data[0] ?? null;
            if (!$model instanceof Model) {
                return;
            }

            if (!$this->shouldAuditModel($model)) {
                return;
            }

            $user = Auth::user();
            if (!$user) {
                return;
            }

            $action = Str::contains($eventName, 'created') ? 'CREATED'
                : (Str::contains($eventName, 'updated') ? 'UPDATED' : 'DELETED');

            (new AuditController())->log(
                model: $model,
                userId: $user->id,
                username: $user->username,
                userRole: $user->roles->first()?->rol_key ?? 'unknown',
                action: $action
            );
        });
    }

    private function shouldAuditModel(Model $model): bool
    {
        $table = $model->getTable();
        $skip = [
            'audits',
            'failed_logins',
            'session_logs',
            'blocked_ips',
            'password_reset_tokens',
            'sessions',
        ];

        return !in_array($table, $skip, true);
    }
}
