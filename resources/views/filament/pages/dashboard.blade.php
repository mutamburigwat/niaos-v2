<x-filament::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <div class="min-w-0 flex-1">
                <h1 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Workspace Overview</h1>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400 truncate">{{ $this->getWorkspaceName() }}</p>
            </div>
            <x-filament::badge :color="$this->getPlanBadgeColor()" class="shrink-0">
                {{ $this->getPlanName() }}
            </x-filament::badge>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <x-heroicon-o-users class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Customers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getCustomerCount() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20">
                    <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Active Leads</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getActiveLeadCount() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/20">
                    <x-heroicon-o-check-circle class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Pending Tasks</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPendingTaskCount() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                    <x-heroicon-o-document-text class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Draft Quotations</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getDraftQuotationCount() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/20">
                    <x-heroicon-o-credit-card class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Active Retainers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getActiveRetainerCount() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20">
                    <x-heroicon-o-lifebuoy class="h-5 w-5 text-red-600 dark:text-red-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Open Support</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getOpenSupportRequestCount() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cyan-50 dark:bg-cyan-900/20">
                    <x-heroicon-o-wrench class="h-5 w-5 text-cyan-600 dark:text-cyan-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Services</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getServiceCount() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-900/50">
                    <x-heroicon-o-folder class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Files</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getFileCount() }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Finance Summary</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Recurring Revenue</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($this->getMonthlyRecurringRevenue(), 2) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding Balance</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">${{ number_format($this->getOutstandingBalance(), 2) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Payments This Month</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($this->getPaymentsThisMonth(), 2) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue Records</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $this->getOverdueBillingRecordCount() }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="space-y-6">
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                    </div>
                    <div class="p-5">
                        @php $activities = $this->getRecentActivity(); @endphp
                        @if ($activities->count() > 0)
                            <div class="space-y-3">
                                @foreach ($activities as $log)
                                    <div class="flex items-center gap-3">
                                        <div class="h-2 w-2 shrink-0 rounded-full {{ match($log->action) {
                                            'created' => 'bg-green-500',
                                            'updated' => 'bg-blue-500',
                                            'deleted' => 'bg-red-500',
                                            default => 'bg-gray-500'
                                        } }}"></div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm text-gray-900 dark:text-white truncate">{{ $log->details }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->actor_name }} &middot; {{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                        <x-filament::badge :color="match($log->entity_type) {
                                            'customer' => 'success',
                                            'lead' => 'warning',
                                            'task' => 'info',
                                            'quotation' => 'primary',
                                            default => 'gray'
                                        }">
                                            {{ ucfirst($log->entity_type) }}
                                        </x-filament::badge>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500">No activity yet. Start by adding your first customer.</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Leads</h3>
                    </div>
                    <div class="p-5">
                        @php $leads = $this->getRecentLeads(); @endphp
                        @if ($leads->count() > 0)
                            <div class="space-y-3">
                                @foreach ($leads as $lead)
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $lead->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $lead->customer?->name ?? 'No customer' }}</p>
                                        </div>
                                        <x-filament::badge :color="match($lead->stage) {
                                            'won' => 'success',
                                            'lost' => 'danger',
                                            'new' => 'info',
                                            'contacted' => 'primary',
                                            default => 'warning'
                                        }">
                                            {{ ucfirst($lead->stage) }}
                                        </x-filament::badge>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500">No leads yet. Start building your pipeline.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Tasks Due Soon</h3>
                    </div>
                    <div class="p-5">
                        @php $tasks = $this->getTasksDueSoon(); @endphp
                        @if ($tasks->count() > 0)
                            <div class="space-y-3">
                                @foreach ($tasks as $task)
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $task->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->customer?->name ?? '—' }}</p>
                                        </div>
                                        <span class="shrink-0 text-xs {{ $task->due_date->isPast() ? 'font-semibold text-red-600' : 'text-gray-500' }}">
                                            {{ $task->due_date->format('M j, Y') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500">No upcoming tasks. Create your first task to stay organised.</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Open Support Requests</h3>
                    </div>
                    <div class="p-5">
                        @php $openRequests = $this->getOpenSupportRequests(); @endphp
                        @if ($openRequests->count() > 0)
                            <div class="space-y-3">
                                @foreach ($openRequests as $req)
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $req->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $req->customer?->name ?? '—' }} &middot; {{ ucfirst($req->priority) }}</p>
                                        </div>
                                        <x-filament::badge :color="match($req->status) {
                                            'open' => 'gray',
                                            'in_progress' => 'info',
                                            'waiting_client' => 'warning',
                                            default => 'gray'
                                        }">
                                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                        </x-filament::badge>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500">No open support requests. All clear.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Active Retainers</h3>
            </div>
            <div class="p-5">
                @php $retainers = $this->getActiveRetainers(); @endphp
                @if ($retainers->count() > 0)
                    <div class="space-y-3">
                        @foreach ($retainers as $retainer)
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $retainer->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $retainer->customer?->name ?? '—' }} &middot; ${{ number_format($retainer->amount, 2) }}/{{ ucfirst($retainer->billing_cycle) }}</p>
                                </div>
                                @if ($retainer->next_billing_date && $retainer->next_billing_date->isPast())
                                    <x-filament::badge color="danger">Overdue</x-filament::badge>
                                @else
                                    <span class="shrink-0 text-xs text-gray-400">{{ $retainer->next_billing_date?->format('M j') ?? '—' }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500">No active retainers. Set up retainers for recurring revenue.</p>
                @endif
            </div>
        </div>
    </div>
</x-filament::page>
