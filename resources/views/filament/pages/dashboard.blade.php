<x-filament::page>
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Welcome, {{ $this->getUser()->name }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Current Workspace: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $this->getWorkspaceName() }}</span>
            </p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Customers</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->getCustomerCount() }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full dark:bg-blue-900/20">
                        <x-heroicon-o-users class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Leads</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->getActiveLeadCount() }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-full dark:bg-amber-900/20">
                        <x-heroicon-o-arrow-trending-up class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->getPendingTaskCount() }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-full dark:bg-purple-900/20">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Draft Quotations</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->getDraftQuotationCount() }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-full dark:bg-emerald-900/20">
                        <x-heroicon-o-document-text class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activity & Tasks Due --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Leads --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Leads</h3>
                </div>
                <div class="p-5">
                    @php $leads = $this->getRecentLeads(); @endphp
                    @if ($leads->count() > 0)
                        <div class="space-y-3">
                            @foreach ($leads as $lead)
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $lead->title }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $lead->customer?->name ?? 'No customer' }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        style="background-color: {{ match($lead->stage) {
                                            'won' => '#dcfce7',
                                            'lost' => '#fee2e2',
                                            'new' => '#dbeafe',
                                            'contacted' => '#e0e7ff',
                                            default => '#fef3c7'
                                        } }}; color: {{ match($lead->stage) {
                                            'won' => '#166534',
                                            'lost' => '#991b1b',
                                            'new' => '#1e40af',
                                            'contacted' => '#3730a3',
                                            default => '#92400e'
                                        } }};">
                                        {{ ucfirst($lead->stage) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No leads yet.</p>
                    @endif
                </div>
            </div>

            {{-- Tasks Due Soon --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tasks Due Soon</h3>
                </div>
                <div class="p-5">
                    @php $tasks = $this->getTasksDueSoon(); @endphp
                    @if ($tasks->count() > 0)
                        <div class="space-y-3">
                            @foreach ($tasks as $task)
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $task->title }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $task->customer?->name ?? '—' }}
                                        </p>
                                    </div>
                                    <span class="text-xs {{ $task->due_date->isPast() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $task->due_date->format('M j, Y') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No upcoming tasks.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            </div>
            <div class="p-5">
                @php $activities = $this->getRecentActivity(); @endphp
                @if ($activities->count() > 0)
                    <div class="space-y-3">
                        @foreach ($activities as $log)
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full {{ match($log->action) {
                                    'created' => 'bg-green-500',
                                    'updated' => 'bg-blue-500',
                                    'deleted' => 'bg-red-500',
                                    default => 'bg-gray-500'
                                } }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white truncate">
                                        {{ $log->details }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->actor_name }} &middot; {{ $log->created_at->diffForHumans() }}
                                    </p>
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
                    <p class="text-sm text-gray-500 dark:text-gray-400">No activity yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-filament::page>
