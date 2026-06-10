{{-- NiaOS Workspace Switcher --}}
<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-heading font-bold text-gray-900 mb-4">Switch Active Workspace</h2>
            <p class="text-sm text-gray-500 mb-6">
                Select a workspace to view and manage its data. You will only see workspaces you are a member of.
            </p>

            <form wire:submit="switch" class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-end">
                    <x-filament::button type="submit" color="primary">
                        Switch Workspace
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
