<x-filament::page>
    @php $workspace = $this->getWorkspace(); @endphp
    @if ($workspace)
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Workspace Settings</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $workspace->business_name }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Workspace Details</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Name</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $workspace->business_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Type</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $workspace->workspace_type?->value ?? '—')) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Industry</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $workspace->industry ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Country</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $workspace->country ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Timezone</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $workspace->timezone ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Plan & Billing</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Plan</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($workspace->plan?->value ?? '—') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Billing Status</span>
                            <x-filament::badge :color="match($workspace->billing_status?->value ?? 'free') {
                                'active' => 'success',
                                'trial' => 'info',
                                'overdue' => 'warning',
                                'suspended', 'cancelled' => 'danger',
                                default => 'gray',
                            }">
                                {{ ucfirst($workspace->billing_status?->value ?? 'free') }}
                            </x-filament::badge>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Your Role</h3>
                    <x-filament::badge :color="match($this->getUserRole()) {
                        'owner' => 'success',
                        'admin' => 'info',
                        default => 'gray',
                    }">
                        {{ ucfirst($this->getUserRole() ?? '—') }}
                    </x-filament::badge>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">No active workspace selected.</p>
        </div>
    @endif
</x-filament::page>
