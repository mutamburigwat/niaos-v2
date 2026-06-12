<div class="fi-simple-page">
    <div class="fi-simple-page-content">
        <header class="fi-simple-header">
            <div class="flex justify-center mb-6">
                <img
                    src="{{ asset('dark_wordmark.png') }}"
                    alt="NiaOS"
                    style="height: 2rem; width: auto; object-fit: contain;"
                />
            </div>

            <h1 class="fi-simple-header-heading">{{ $this->getHeading() }}</h1>

            @if (filled($this->getSubheading()))
                <p class="fi-simple-header-subheading">{{ $this->getSubheading() }}</p>
            @endif
        </header>

        {{ $this->content }}
    </div>
</div>
