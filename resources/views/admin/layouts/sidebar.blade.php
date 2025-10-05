<aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
        <div>
            <b class="font-black">Wox's</b> Barbershop
        </div>
    </div>

    <div class="menu is-menu-main">

        {{-- Sidebar untuk Admin --}}
        @hasrole('admin')
            <p class="menu-label">General</p>
            <ul class="menu-list">
                <li class="active">
                    <a href="{{ route('dashboard') }}">
                        <span class="icon"><i class="mdi mdi-view-dashboard"></i></span>
                        <span class="menu-item-label">Dashboard</span>
                    </a>
                </li>
            </ul>

            <p class="menu-label">Management</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                        <span class="menu-item-label">Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.services.index') }}">
                        <span class="icon"><i class="mdi mdi-scissors-cutting"></i></span>
                        <span class="menu-item-label">Services</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.products.index') }}">
                        <span class="icon"><i class="mdi mdi-package-variant"></i></span>
                        <span class="menu-item-label">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hairstyles.index') }}">
                        <span class="icon"><i class="mdi mdi-hair-dryer"></i></span>
                        <span class="menu-item-label">Hairstyles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hairstyles.score.index') }}">
                        <span class="icon"><i class="mdi mdi-chart-bar"></i></span>
                        <span class="menu-item-label">Hairstyle Score</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookings.index') }}">
                        <span class="icon"><i class="mdi mdi-calendar-check"></i></span>
                        <span class="menu-item-label">Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}">
                        <span class="icon"><i class="mdi mdi-cash-register"></i></span>
                        <span class="menu-item-label">Transactions</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.system.index') }}">
                        <span class="icon"><i class="mdi mdi-backup-restore"></i></span>
                        <span class="menu-item-label">Backup & Restore</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.feedbacks.index') }}">
                        <span class="icon"><i class="mdi mdi-comment-text-outline"></i></span>
                        <span class="menu-item-label">Feedbacks</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.roles.index') }}">
                        <span class="icon"><i class="mdi mdi-shield-key"></i></span>
                        <span class="menu-item-label">Roles</span>
                    </a>
                </li>


            </ul>
        @endhasrole

        {{-- Sidebar untuk Pegawai --}}
        @hasrole('pegawai')
            <p class="menu-label">General</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <span class="icon"><i class="mdi mdi-view-dashboard"></i></span>
                        <span class="menu-item-label">Dashboard</span>
                    </a>
                </li>
            </ul>

            <p class="menu-label">Transaksi</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.bookings.index') }}">
                        <span class="icon"><i class="mdi mdi-calendar-check"></i></span>
                        <span class="menu-item-label">Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}">
                        <span class="icon"><i class="mdi mdi-cash-register"></i></span>
                        <span class="menu-item-label">Transactions</span>
                    </a>
                </li>
            </ul>
        @endhasrole

    </div>
</aside>
