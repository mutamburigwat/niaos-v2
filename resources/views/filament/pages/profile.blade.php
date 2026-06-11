<x-filament::page>
    <div class="space-y-6">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-lg font-bold text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                    {{ strtoupper(substr($this->getUser()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $this->getUser()->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $this->getUser()->email }}</p>
                </div>
            </div>
        </div>

        @php $workspace = $this->getActiveWorkspace(); @endphp
        @if ($workspace)
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Active Workspace</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Name</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $workspace->business_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Plan</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($workspace->plan?->value ?? '—') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Type</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $workspace->workspace_type?->value ?? '—')) }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
