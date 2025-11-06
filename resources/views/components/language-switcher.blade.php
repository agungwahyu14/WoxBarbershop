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

<div class="relative inline-block text-left">
    <!-- Language Switcher Button -->
    <button id="language-switcher-btn"
        class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors duration-200"
        aria-expanded="false" aria-haspopup="true">
        <span class="mr-2 text-lg">{{ $languages[$currentLang]['flag'] }}</span>
        <span class="hidden sm:inline">{{ $languages[$currentLang]['name'] }}</span>
        <span class="sm:hidden">{{ strtoupper($currentLang) }}</span>
        <svg class="w-4 h-4 ml-2 -mr-1 transition-transform duration-200" id="dropdown-icon"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Language Dropdown Menu -->
    <div id="language-dropdown"
        class="hidden absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white border border-gray-200 rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
        role="menu" aria-orientation="vertical">

        <div class="py-1" role="none">
            <div class="px-4 py-2 text-sm font-medium text-gray-900 border-b border-gray-100">
                <i class="fas fa-globe mr-2 text-black"></i>
                {{ __('general.change_language') }}
            </div>

            @foreach ($languages as $lang => $data)
                <a href="{{ route('language.switch', $lang) }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-150 {{ $currentLang === $lang ? 'bg-black/10 text-black font-semibold' : '' }}"
                    role="menuitem">
                    <span class="mr-3 text-lg">{{ $data['flag'] }}</span>
                    <span>{{ $data['name'] }}</span>
                    @if ($currentLang === $lang)
                        <i class="fas fa-check ml-auto text-black"></i>
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
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-black"></div>
            <span class="ml-3 text-gray-700">{{ __('general.loading') }}</span>
        </div>
    </div>
</div>

{{-- JavaScript for enhanced functionality --}}
<script>
    // JavaScript untuk membuka dan menutup dropdown language switcher
    document.addEventListener("DOMContentLoaded", function() {
        const languageButton = document.getElementById('language-switcher-btn');
        const dropdownMenu = document.getElementById('language-dropdown');
        const dropdownIcon = document.getElementById('dropdown-icon');

        // Menangani event klik untuk membuka/tutup dropdown
        languageButton.addEventListener('click', function(event) {
            // Mencegah event klik untuk propagasi dan reload halaman
            event.preventDefault();

            // Toggle dropdown visibility
            dropdownMenu.classList.toggle('hidden');

            // Rotate icon jika dropdown terbuka
            dropdownIcon.classList.toggle('rotate-180');
        });

        // Menangani event klik di luar dropdown untuk menutupnya
        document.addEventListener('click', function(event) {
            if (!languageButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                // Tutup dropdown jika klik di luar tombol dan menu
                dropdownMenu.classList.add('hidden');
                dropdownIcon.classList.remove('rotate-180');
            }
        });
    });
</script>
