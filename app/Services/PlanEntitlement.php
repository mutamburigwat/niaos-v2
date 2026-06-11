<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Workspace;

class PlanEntitlement
{
    protected static function getPlanRecord(Workspace $workspace): ?Plan
    {
        return Plan::where('key', $workspace->plan->value)->first();
    }

    protected static function getLimit(Workspace $workspace, string $key, int $default = 0): int
    {
        $plan = static::getPlanRecord($workspace);
        if (! $plan || ! is_array($plan->limits)) {
            return $default;
        }

        return (int) ($plan->limits[$key] ?? $default);
    }

    protected static function isUnlimited(int $value): bool
    {
        return $value < 0;
    }

    public static function canAddUser(Workspace $workspace): bool
    {
        return static::underLimit($workspace, 'max_users', $workspace->members()->count());
    }

    public static function canAddCustomer(Workspace $workspace): bool
    {
        return static::underLimit($workspace, 'max_customers', $workspace->customers()->count());
    }

    public static function canAddLead(Workspace $workspace): bool
    {
        return static::underLimit($workspace, 'max_leads', $workspace->leads()->count());
    }

    public static function canAddTask(Workspace $workspace): bool
    {
        return static::underLimit($workspace, 'max_tasks', $workspace->tasks()->count());
    }

    public static function canAddQuotation(Workspace $workspace): bool
    {
        return static::underLimit($workspace, 'max_quotations', $workspace->quotations()->count());
    }

    public static function moduleEnabled(Workspace $workspace, string $module): bool
    {
        $plan = static::getPlanRecord($workspace);
        if (! $plan || ! is_array($plan->features)) {
            return false;
        }

        return array_key_exists($module, $plan->features);
    }

    public static function getUsageStats(Workspace $workspace): array
    {
        $plan = static::getPlanRecord($workspace);
        $limits = $plan?->limits ?? [];

        return [
            'users' => [
                'current' => $workspace->members()->count(),
                'max' => (int) ($limits['max_users'] ?? -1),
            ],
            'customers' => [
                'current' => $workspace->customers()->count(),
                'max' => (int) ($limits['max_customers'] ?? -1),
            ],
            'leads' => [
                'current' => $workspace->leads()->count(),
                'max' => (int) ($limits['max_leads'] ?? -1),
            ],
            'tasks' => [
                'current' => $workspace->tasks()->count(),
                'max' => (int) ($limits['max_tasks'] ?? -1),
            ],
            'quotations' => [
                'current' => $workspace->quotations()->count(),
                'max' => (int) ($limits['max_quotations'] ?? -1),
            ],
        ];
    }

    public static function getPlanName(Workspace $workspace): string
    {
        $plan = static::getPlanRecord($workspace);

        return $plan?->name ?? ucfirst(str_replace('_', ' ', $workspace->plan->value));
    }

    protected static function underLimit(Workspace $workspace, string $limitKey, int $currentCount): bool
    {
        $max = static::getLimit($workspace, $limitKey, -1);

        if (static::isUnlimited($max)) {
            return true;
        }

        return $currentCount < $max;
    }
}
