<x-filament::page>
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Platform Overview
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Welcome back, {{ Auth::user()->name }}. Here is the current state of the platform.
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                        <x-heroicon-o-building-office class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Workspaces</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getTotalWorkspaces() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getActiveWorkspaces() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20">
                        <x-heroicon-o-clock class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Trials</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getTrialWorkspaces() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20">
                        <x-heroicon-o-minus-circle class="h-5 w-5 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Suspended</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getSuspendedWorkspaces() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/20">
                        <x-heroicon-o-users class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getTotalUsers() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/20">
                        <x-heroicon-o-credit-card class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Paid Clients</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPaidClients() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
