<x-filament::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Platform Overview
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Welcome back, {{ Auth::user()->name }}. Here is the state of the platform.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Workspaces</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->getTotalWorkspaces() }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full dark:bg-blue-900/20">
                        <x-heroicon-o-building-office class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Workspaces</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $this->getActiveWorkspaces() }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full dark:bg-green-900/20">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Suspended</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $this->getSuspendedWorkspaces() }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-full dark:bg-red-900/20">
                        <x-heroicon-o-minus-circle class="w-6 h-6 text-red-600 dark:text-red-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Onboarding</p>
                        <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $this->getOnboardingWorkspaces() }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-full dark:bg-amber-900/20">
                        <x-heroicon-o-cog class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->getTotalUsers() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-full dark:bg-indigo-900/20">
                        <x-heroicon-o-users class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ $this->getPlatformAdmins() }} platform admins</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Customers</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $this->getTotalCustomers() }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-full dark:bg-purple-900/20">
                        <x-heroicon-o-user-group class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Leads / Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $this->getTotalLeads() }} / {{ $this->getTotalTasks() }}
                        </p>
                    </div>
                    <div class="p-3 bg-teal-50 rounded-full dark:bg-teal-900/20">
                        <x-heroicon-o-arrow-trending-up class="w-6 h-6 text-teal-600 dark:text-teal-400" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
