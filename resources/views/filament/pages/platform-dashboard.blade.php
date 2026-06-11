<x-filament::page>
    <div class="space-y-6">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Monitor workspaces, users, plans and access across the platform.
        </p>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <x-heroicon-o-building-office class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Total Workspaces</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getTotalWorkspaces() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20">
                    <x-heroicon-o-check-circle class="h-5 w-5 text-green-600 dark:text-green-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getActiveWorkspaces() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20">
                    <x-heroicon-o-clock class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Trials</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getTrialWorkspaces() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20">
                    <x-heroicon-o-minus-circle class="h-5 w-5 text-red-600 dark:text-red-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Suspended</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getSuspendedWorkspaces() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/20">
                    <x-heroicon-o-credit-card class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Paid Clients</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPaidClients() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-900/50">
                    <x-heroicon-o-building-library class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Internal</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getInternalWorkspaces() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/20">
                    <x-heroicon-o-users class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getTotalUsers() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cyan-50 dark:bg-cyan-900/20">
                    <x-heroicon-o-shield-check class="h-5 w-5 text-cyan-600 dark:text-cyan-400" />
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Platform Admins</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPlatformAdmins() }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Workspaces</h3>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Last 5 workspaces created on the platform.</p>
                </div>
                @php $workspaces = $this->getRecentWorkspaces(); @endphp
                @if ($workspaces->count() > 0)
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($workspaces as $ws)
                            <div class="flex items-center justify-between px-5 py-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $ws->business_name }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $ws->workspace_type?->value ?? '—')) }} &middot; {{ ucfirst(str_replace('_', ' ', $ws->plan?->value ?? '—')) }}</p>
                                </div>
                                <span class="ml-4 shrink-0 text-xs text-gray-400">{{ $ws->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No workspaces created yet.</p>
                @endif
            </div>

            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Users</h3>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Last 5 users registered on the platform.</p>
                </div>
                @php $users = $this->getRecentUsers(); @endphp
                @if ($users->count() > 0)
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($users as $u)
                            <div class="flex items-center justify-between px-5 py-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $u->name }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $u->email }}</p>
                                </div>
                                <div class="ml-4 flex shrink-0 items-center gap-2">
                                    @if ($u->is_platform_admin)
                                        <span class="inline-flex items-center rounded-full bg-cyan-50 px-2 py-0.5 text-xs font-medium text-cyan-700 dark:bg-cyan-900/20 dark:text-cyan-300">Admin</span>
                                    @endif
                                    <span class="text-xs text-gray-400">{{ $u->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No users registered yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-filament::page>
