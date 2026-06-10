<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\FileRecord;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Task;
use App\Observers\ActivityLogObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Customer::observe(ActivityLogObserver::class);
        Lead::observe(ActivityLogObserver::class);
        Task::observe(ActivityLogObserver::class);
        Quotation::observe(ActivityLogObserver::class);
        FileRecord::observe(ActivityLogObserver::class);
    }
}
