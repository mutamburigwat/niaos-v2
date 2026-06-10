<?php

namespace App\Models;

use App\Enums\BillingStatus;
use App\Enums\Plan;
use App\Enums\WorkspaceStatus;
use App\Enums\WorkspaceType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    use HasUuids;

    protected $fillable = [
        'business_name',
        'business_type',
        'workspace_type',
        'industry',
        'country',
        'city',
        'address',
        'base_currency',
        'timezone',
        'plan',
        'billing_status',
        'status',
        'onboarding_status',
        'channels_config',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'channels_config' => 'array',
            'status' => WorkspaceStatus::class,
            'plan' => Plan::class,
            'workspace_type' => WorkspaceType::class,
            'billing_status' => BillingStatus::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function fileRecords(): HasMany
    {
        return $this->hasMany(FileRecord::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function joseConversations(): HasMany
    {
        return $this->hasMany(JoseConversation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', WorkspaceStatus::Active);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', WorkspaceStatus::Suspended);
    }

    public function scopeOnboarding($query)
    {
        return $query->where('status', WorkspaceStatus::Onboarding);
    }

    public function scopeInternal($query)
    {
        return $query->where('workspace_type', WorkspaceType::Internal);
    }

    public function scopePaidClient($query)
    {
        return $query->where('workspace_type', WorkspaceType::PaidClient);
    }

    public function isInternal(): bool
    {
        return $this->workspace_type === WorkspaceType::Internal;
    }
}
