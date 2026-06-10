<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\FileRecord;
use App\Models\JoseConversation;
use App\Models\JoseMessage;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use App\Policies\ActivityLogPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\FileRecordPolicy;
use App\Policies\JoseConversationPolicy;
use App\Policies\JoseMessagePolicy;
use App\Policies\LeadPolicy;
use App\Policies\QuotationPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkspacePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Customer::class => CustomerPolicy::class,
        Lead::class => LeadPolicy::class,
        Task::class => TaskPolicy::class,
        Quotation::class => QuotationPolicy::class,
        FileRecord::class => FileRecordPolicy::class,
        ActivityLog::class => ActivityLogPolicy::class,
        JoseConversation::class => JoseConversationPolicy::class,
        JoseMessage::class => JoseMessagePolicy::class,
        Workspace::class => WorkspacePolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
