<aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
        <div>
            <b class="font-black">Wox's</b> Barbershop
        </div>
    </div>

    <div class="menu is-menu-main">

        {{-- Sidebar untuk Admin --}}
        @hasrole('admin')
            <p class="menu-label">{{ __('menu.general') }}</p>
            <ul class="menu-list">
                <li class="active">
                    <a href="{{ route('dashboard') }}">
                        <span class="icon"><i class="mdi mdi-view-dashboard"></i></span>
                        <span class="menu-item-label">{{ __('menu.dashboard') }}</span>
                    </a>
                </li>
            </ul>

            <p class="menu-label">{{ __('menu.management') }}</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                        <span class="menu-item-label">{{ __('menu.users') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.services.index') }}">
                        <span class="icon"><i class="mdi mdi-scissors-cutting"></i></span>
                        <span class="menu-item-label">{{ __('menu.services') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.products.index') }}">
                        <span class="icon"><i class="mdi mdi-package-variant"></i></span>
                        <span class="menu-item-label">{{ __('menu.products') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hairstyles.index') }}">
                        <span class="icon"><i class="mdi mdi-hair-dryer"></i></span>
                        <span class="menu-item-label">{{ __('menu.hairstyles') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hairstyles.score.index') }}">
                        <span class="icon"><i class="mdi mdi-chart-bar"></i></span>
                        <span class="menu-item-label">{{ __('menu.hairstyle_score') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookings.index') }}">
                        <span class="icon"><i class="mdi mdi-calendar-check"></i></span>
                        <span class="menu-item-label">{{ __('menu.bookings') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}">
                        <span class="icon"><i class="mdi mdi-cash-register"></i></span>
                        <span class="menu-item-label">{{ __('menu.transactions') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.loyalty.index') }}">
                        <span class="icon"><i class="mdi mdi-star-circle"></i></span>
                        <span class="menu-item-label">{{ __('menu.loyalty') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.system.index') }}">
                        <span class="icon"><i class="mdi mdi-backup-restore"></i></span>
                        <span class="menu-item-label">{{ __('menu.backup_restore') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.feedbacks.index') }}">
                        <span class="icon"><i class="mdi mdi-comment-text-outline"></i></span>
                        <span class="menu-item-label">{{ __('menu.feedbacks') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.roles.index') }}">
                        <span class="icon"><i class="mdi mdi-shield-key"></i></span>
                        <span class="menu-item-label">{{ __('menu.roles') }}</span>
                    </a>
                </li>


            </ul>
        @endhasrole

        {{-- Sidebar untuk Pegawai --}}
        @hasrole('pegawai')
            <p class="menu-label">{{ __('menu.general') }}</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <span class="icon"><i class="mdi mdi-view-dashboard"></i></span>
                        <span class="menu-item-label">{{ __('menu.dashboard') }}</span>
                    </a>
                </li>
            </ul>

            <p class="menu-label">{{ __('menu.transactions') }}</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.bookings.index') }}">
                        <span class="icon"><i class="mdi mdi-calendar-check"></i></span>
                        <span class="menu-item-label">{{ __('menu.bookings') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}">
                        <span class="icon"><i class="mdi mdi-cash-register"></i></span>
                        <span class="menu-item-label">{{ __('menu.transactions') }}</span>
                    </a>
                </li>
            </ul>
        @endhasrole

    </div>
</aside>
