{{-- NiaOS Jose Assistant - Placeholder --}}
<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <x-filament::icon name="heroicon-o-sparkles" class="w-6 h-6 text-amber-600" />
                </div>
                <div>
                    <h2 class="text-lg font-heading font-bold text-gray-900">Jose Assistant</h2>
                    <p class="text-sm text-gray-500">AI-powered business assistant powered by Groq</p>
                </div>
            </div>

            @unless($groq_configured)
                <div class="p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                    <strong>Groq API not configured.</strong> Set <code>GROQ_API_KEY</code> in your <code>.env</code> file to enable AI features.
                </div>
            @endunless

            <div class="mt-6 p-8 text-center">
                <x-filament::icon name="heroicon-o-chat-bubble-left-right" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-base font-semibold text-gray-700 mb-2">AI Chat Coming Soon</h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto">
                    Jose Assistant will enable intent classification, suggested replies, entity extraction, and daily business briefings powered by Groq's fast inference API.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
