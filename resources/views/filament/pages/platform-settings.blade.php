<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Settings</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Configure global NiaOS platform behaviour. Changes take effect immediately.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">General</h2>
            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Platform Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">NiaOS</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Default Workspace Plan</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">Starter</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Default Billing Status<br><span class="text-xs text-gray-400">for new paid clients</span></dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">Trial</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Contact</h2>
            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Support Contact</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">support@niaos.io</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">System Notes</h2>
            <div class="px-4 py-4 sm:px-0">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    NiaOS v2 — Platform Console. Billing automation, payment processing, and persistent settings
                    are not yet implemented. Configuration values shown are defaults used during workspace onboarding.
                </p>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 dark:bg-amber-900/10 dark:border-amber-800">
            <div class="flex">
                <x-heroicon-o-information-circle class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                <p class="ml-3 text-sm text-amber-700 dark:text-amber-300">
                    Settings management UI is a placeholder. To change platform defaults, update the
                    <code class="text-amber-800 dark:text-amber-200 font-mono text-xs">WorkspaceProvisioningService</code>
                    or the form defaults in
                    <code class="text-amber-800 dark:text-amber-200 font-mono text-xs">CreateClientWorkspace</code>.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
