<x-filament-panels::page>
    <div class="space-y-6">
        @if ($created)
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="mb-6 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-success-50 dark:bg-success-900/20">
                        <x-heroicon-o-check-circle class="h-8 w-8 text-success-600 dark:text-success-400" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Client Workspace Created</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The workspace and owner account have been set up successfully.</p>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 space-y-3 dark:bg-gray-900/50">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Workspace Name</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $summary['workspace_name'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Workspace Type</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $summary['workspace_type'])) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Plan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $summary['plan'])) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Billing Status</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ ucfirst($summary['billing_status']) }}</span>
                    </div>
                    <hr class="border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Owner Email</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $summary['owner_email'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Temporary Password</span>
                        <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">{{ $summary['temporary_password'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Login URL</span>
                        <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">{{ $summary['login_url'] }}</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-center gap-3">
                    <x-filament::button color="gray" tag="a" href="{{ url('/platform/workspaces') }}">
                        Go to Workspaces
                    </x-filament::button>
                    <x-filament::button color="primary" wire:click="resetForm">
                        Onboard Another
                    </x-filament::button>
                </div>
            </div>
        @else
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">New Client Workspace</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Create a new workspace and owner account in one step. The workspace will be set to active immediately.
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <form wire:submit="create" class="space-y-6">
                    {{ $this->form }}

                    <div class="flex justify-end border-t border-gray-200 pt-6 dark:border-gray-700">
                        <x-filament::button type="submit" color="primary">
                            Create Workspace
                        </x-filament::button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-filament-panels::page>
