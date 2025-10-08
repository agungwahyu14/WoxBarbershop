{{-- Language Switcher Component --}}
@php
    $currentLang = app()->getLocale();
    $languages = [
        'id' => [
            'code' => 'id',
            'name' => __('general.indonesian'),
            'flag' => '🇮🇩',
        ],
        'en' => [
            'code' => 'en',
            'name' => __('general.english'),
            'flag' => '🇺🇸',
        ],
    ];
@endphp

<div class="relative inline-block text-left" x-data="{ open: false }">
    <!-- Language Switcher Button -->
    <button @click="open = !open"
        class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d4af37] transition-colors duration-200"
        aria-expanded="true" aria-haspopup="true">
        <span class="mr-2 text-lg">{{ $languages[$currentLang]['flag'] }}</span>
        <span class="hidden sm:inline">{{ $languages[$currentLang]['name'] }}</span>
        <span class="sm:hidden">{{ strtoupper($currentLang) }}</span>
        <svg class="w-4 h-4 ml-2 -mr-1 transition-transform duration-200" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Language Dropdown Menu -->
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white border border-gray-200 rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
        role="menu" aria-orientation="vertical">

        <div class="py-1" role="none">
            <div class="px-4 py-2 text-sm font-medium text-gray-900 border-b border-gray-100">
                <i class="fas fa-globe mr-2 text-[#d4af37]"></i>
                {{ __('general.change_language') }}
            </div>

            @foreach ($languages as $lang => $data)
                <a href="{{ route('language.switch', $lang) }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-150 {{ $currentLang === $lang ? 'bg-[#d4af37]/10 text-[#d4af37] font-semibold' : '' }}"
                    role="menuitem">
                    <span class="mr-3 text-lg">{{ $data['flag'] }}</span>
                    <span>{{ $data['name'] }}</span>
                    @if ($currentLang === $lang)
                        <i class="fas fa-check ml-auto text-[#d4af37]"></i>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Loading indicator for language switch --}}
<div id="language-loading" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
        <div class="flex items-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#d4af37]"></div>
            <span class="ml-3 text-gray-700">{{ __('general.loading') }}</span>
        </div>
    </div>
</div>

{{-- JavaScript for enhanced functionality --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to language links
        const languageLinks = document.querySelectorAll('a[href*="language/switch"]');

        languageLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Show loading indicator
                const loading = document.getElementById('language-loading');
                if (loading) {
                    loading.classList.remove('hidden');
                }

                // Optional: Add small delay for better UX
                setTimeout(() => {
                    // Allow the link to proceed
                    window.location.href = this.href;
                }, 300);
            });
        });
    });
</script>
