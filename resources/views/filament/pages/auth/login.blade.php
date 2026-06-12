<div class="min-h-screen bg-cover bg-center bg-no-repeat relative" style="background-image: url('{{ asset('login.png') }}')">
    <div class="absolute inset-0 bg-black/55"></div>
    <div class="relative z-10 flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-[460px] bg-[rgba(20,20,24,0.55)] backdrop-blur-lg border border-[#C9A84C]/20 rounded-2xl p-10 shadow-2xl">
            @if ($this->hasLogo())
                <div class="text-center mb-6">
                    <x-filament-panels::logo />
                </div>
            @endif

            <h2 class="text-center text-xl font-semibold text-[#C9A84C] mb-1">
                {{ $this->getHeading() }}
            </h2>

            @if (filled($this->getSubheading()))
                <p class="text-center text-sm text-white/60 mb-6">
                    {{ $this->getSubheading() }}
                </p>
            @endif

            {{ $this->content }}
        </div>
    </div>
</div>
