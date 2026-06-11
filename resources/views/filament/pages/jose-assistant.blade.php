{{-- NiaOS Jose Assistant - Placeholder --}}
<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                    <x-filament::icon name="heroicon-o-sparkles" class="h-6 w-6 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Jose Assistant</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">AI-powered business assistant powered by Groq</p>
                </div>
            </div>

            @unless($groq_configured)
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-900/20 dark:text-amber-300">
                    <strong>Groq API not configured.</strong> Set <code>GROQ_API_KEY</code> in your <code>.env</code> file to enable AI features.
                </div>
            @endunless

            <div class="mt-6 p-8 text-center">
                <x-filament::icon name="heroicon-o-chat-bubble-left-right" class="mx-auto mb-4 h-16 w-16 text-gray-300 dark:text-gray-600" />
                <h3 class="mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">AI Chat Coming Soon</h3>
                <p class="mx-auto max-w-md text-sm text-gray-500 dark:text-gray-400">
                    Jose Assistant will enable intent classification, suggested replies, entity extraction, and daily business briefings powered by Groq's fast inference API.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
