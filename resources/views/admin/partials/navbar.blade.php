<nav id="navbar-main" class="navbar is-fixed-top">
    <div class="navbar-brand">
        <a class="navbar-item mobile-aside-button">
            <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-brand is-right">
        <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
            <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-menu" id="navbar-menu">
        <div class="navbar-end">
            <!-- Language Switcher -->
            <div class="navbar-item">

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
                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        aria-expanded="true" aria-haspopup="true">
                        <span class="mr-2 text-lg">{{ $languages[$currentLang]['flag'] }}</span>
                        <span class="hidden sm:inline">{{ $languages[$currentLang]['name'] }}</span>
                        <span class="sm:hidden">{{ strtoupper($currentLang) }}</span>
                        <svg class="w-4 h-4 ml-2 -mr-1 transition-transform duration-200"
                            :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Language Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white border border-gray-200 rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                        role="menu" aria-orientation="vertical">

                        <div class="py-1" role="none">
                            <div class="px-4 py-2 text-sm font-medium text-gray-900 border-b border-gray-100">
                                <i class="mdi mdi-web mr-2 text-blue-500"></i>
                                {{ __('general.change_language') }}
                            </div>

                            @foreach ($languages as $lang => $data)
                                <a href="{{ route('language.switch', $lang) }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-150 {{ $currentLang === $lang ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}"
                                    role="menuitem">
                                    <span class="mr-3 text-lg">{{ $data['flag'] }}</span>
                                    <span>{{ $data['name'] }}</span>
                                    @if ($currentLang === $lang)
                                        <i class="mdi mdi-check ml-auto text-blue-500"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="navbar-item dropdown has-divider has-user-avatar">
                <a class="navbar-link">

                    <div class="is-user-name"><span>{{ auth()->user()->name }}</span></div>
                    <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
                </a>
                <div class="navbar-dropdown">
                    {{-- <a href="{{ route('profile.edit') }}" class="navbar-item">
                        <span class="icon"><i class="mdi mdi-account"></i></span>
                        <span>My Profile</span>
                    </a> --}}
                    <hr class="navbar-divider">
                    <!-- Form Logout Breeze -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="navbar-item"
                            style="background: none; border: none; cursor: pointer;">
                            <span class="icon"><i class="mdi mdi-logout"></i></span>
                            <span>{{ __('auth.logout') }}</span>
                        </button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Language Switcher JavaScript -->
<script>
// Fix: Remove duplicate event listener and use proper initialization
(function() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminLanguageSwitcher);
    } else {
        initAdminLanguageSwitcher();
    }

    function initAdminLanguageSwitcher() {
        // Add click event listeners to language links for loading indicator
        const languageLinks = document.querySelectorAll('a[href*="language/switch"]');

        languageLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Show simple loading feedback
                this.style.opacity = '0.6';
                this.style.pointerEvents = 'none';

                // Optional success message with SweetAlert if available
                if (typeof Swal !== 'undefined') {
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Language Changed!',
                            text: 'Bahasa telah diubah / Language has been changed',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }, 100);
                }
            });
        });
    }
})();
</script>
