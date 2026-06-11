<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Settings</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Configure global NiaOS platform behaviour. Changes take effect immediately.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}

                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-filament::button type="submit" color="primary">
                        Save Settings
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
