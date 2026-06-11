<x-filament-panels::page>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Platform Settings</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Configure global NiaOS platform behaviour. Changes take effect immediately.
            </p>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}

                <div class="flex justify-end border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-filament::button type="submit" color="primary">
                        Save Settings
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
