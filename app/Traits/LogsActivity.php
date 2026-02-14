<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\System\SysActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->logActivity('create', 'created');
        });

        static::updated(function ($model) {
            $model->logActivity('update', 'updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', 'deleted');
        });

        static::restored(function ($model) {
            $model->logActivity('restore', 'restored');
        });
    }

    /**
     * Log activity to sys_activity_logs table
     */
    public function logActivity(string $action, string $description): void
    {
        $user = Auth::user();

        SysActivityLog::create([
            'sys_user_id' => $user?->id,
            'action' => $action,
            'module' => $this->getModuleName(),
            'reference_table' => $this->getTable(),
            'reference_id' => $this->id,
            'description' => ucfirst($description) . ' ' . $this->getModuleName() . ' ID: ' . $this->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get module name from class name
     */
    protected function getModuleName(): string
    {
        $className = class_basename($this);

        // Remove prefixes like Mst, Trx, Sys
        $moduleName = preg_replace('/^(Mst|Trx|Sys)/', '', $className);

        // Convert camelCase to words
        $moduleName = preg_replace('/(?<!^)([A-Z])/', ' $1', $moduleName);

        return $moduleName;
    }
}
