<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Available Plans</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                NiaOS subscription plans. Billing and payment collection are not yet active.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($this->getPlans() as $plan)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan['name'] }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $plan['badge_color'] }}-100 text-{{ $plan['badge_color'] }}-800 dark:bg-{{ $plan['badge_color'] }}-900/20 dark:text-{{ $plan['badge_color'] }}-400">
                            {{ $plan['price'] }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $plan['description'] }}</p>
                    <div class="mt-4 text-xs text-gray-400 dark:text-gray-500">
                        Plan key: <code class="text-gray-600 dark:text-gray-300">{{ $plan['key'] }}</code>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
