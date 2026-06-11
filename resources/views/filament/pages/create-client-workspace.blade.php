<x-filament-panels::page>
    <div class="space-y-6">
        @if ($created)
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center w-16 h-16 bg-success-50 rounded-full mb-4 dark:bg-success-900/20">
                        <x-heroicon-o-check-circle class="w-8 h-8 text-success-600 dark:text-success-400" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Client Workspace Created</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">The workspace and owner account have been set up successfully.</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 space-y-3 dark:bg-gray-900/50">
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
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h2 class="text-lg font-heading font-bold text-gray-900 dark:text-white mb-2">New Client Workspace</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Create a new workspace and owner account in one step. The workspace will be set to active immediately.
                </p>

                <form wire:submit="create" class="space-y-4">
                    {{ $this->form }}

                    <div class="flex justify-end pt-4">
                        <x-filament::button type="submit" color="primary">
                            Create Workspace
                        </x-filament::button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-filament-panels::page>
