@php
    use App\Services\PlanEntitlement;
    $stats = PlanEntitlement::getUsageStats($workspace);
    $planName = PlanEntitlement::getPlanName($workspace);
@endphp

<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $planName }}</p>
        </div>

        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Billing</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                <x-filament::badge :color="match($workspace->billing_status?->value ?? 'free') {
                    'free' => 'gray',
                    'trial' => 'info',
                    'active' => 'success',
                    'overdue' => 'warning',
                    'suspended' => 'danger',
                    'cancelled' => 'danger',
                    default => 'gray',
                }">
                    {{ ucfirst($workspace->billing_status?->value ?? 'free') }}
                </x-filament::badge>
            </p>
        </div>

        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Workspace Type</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                <x-filament::badge :color="match($workspace->workspace_type?->value ?? 'paid_client') {
                    'internal' => 'info',
                    'paid_client' => 'success',
                    'demo' => 'warning',
                    'partner' => 'primary',
                    default => 'gray',
                }">
                    {{ ucfirst(str_replace('_', ' ', $workspace->workspace_type?->value ?? 'paid_client')) }}
                </x-filament::badge>
            </p>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Limits & Usage</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
            @foreach (['users', 'customers', 'leads', 'tasks', 'quotations'] as $resource)
                @php
                    $current = $stats[$resource]['current'];
                    $max = $stats[$resource]['max'];
                    $isUnlimited = $max < 0;
                    $pct = $isUnlimited ? 0 : ($max > 0 ? round(($current / $max) * 100) : 0);
                    $nearLimit = !$isUnlimited && $pct >= 80 && $pct < 100;
                    $atLimit = !$isUnlimited && $current >= $max;
                    $barColor = $atLimit ? 'bg-red-500' : ($nearLimit ? 'bg-amber-500' : 'bg-emerald-500');
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-md p-3 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $resource }}</span>
                        <div class="flex items-center gap-1">
                            @if ($atLimit)
                                <x-filament::badge color="danger" size="xs">Limit reached</x-filament::badge>
                            @elseif ($nearLimit)
                                <x-filament::badge color="warning" size="xs">Near limit</x-filament::badge>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $current }}</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            @if ($isUnlimited)
                                / &infin;
                            @else
                                / {{ $max }}
                            @endif
                        </span>
                    </div>
                    @unless ($isUnlimited)
                        <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ min($pct, 100) }}%"></div>
                        </div>
                    @endunless
                </div>
            @endforeach
        </div>
    </div>
</div>
