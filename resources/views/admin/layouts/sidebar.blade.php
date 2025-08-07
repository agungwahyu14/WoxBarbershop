<aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
        <div>
            <b class="font-black">Wox's</b> Barbershop
        </div>
    </div>

    <div class="menu is-menu-main">
        <p class="menu-label">General</p>
        <ul class="menu-list">
            <li class="active">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
                    <span class="menu-item-label">Dashboard</span>
                </a>
            </li>
        </ul>

        <p class="menu-label">Management</p>
        <ul class="menu-list">
            <li>
                <a class="dropdown">
                    <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                    <span class="menu-item-label">User Management</span>
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                </a>
                <ul>
                    <li><a href="{{ route('users.index') }}"><span>Users</span></a></li>
                </ul>
            </li>

            <li>
                <a class="dropdown">
                    <span class="icon"><i class="mdi mdi-scissors-cutting"></i></span>
                    <span class="menu-item-label">Service Management</span>
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                </a>
                <ul>
                    <li><a href="{{ route('services.index') }}"><span>Services</span></a></li>
                    <li><a href="{{ route('hairstyles.index') }}"><span>Hairstyles</span></a></li>
                    {{-- <li><a href="{{ route('recommendations.index') }}"><span>Recommendations</span></a></li> --}}
                </ul>
            </li>

            <li>
                <a class="dropdown">
                    <span class="icon"><i class="mdi mdi-calendar-check"></i></span>
                    <span class="menu-item-label">Booking & Transaction</span>
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                </a>
                <ul>
                    <li><a href="{{ route('bookings.index') }}"><span>Bookings</span></a></li>
                    <li><a href="{{ route('transactions.index') }}"><span>Transactions</span></a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('loyalties.index') }}">
                    <span class="icon"><i class="mdi mdi-star-circle"></i></span>
                    <span class="menu-item-label">Loyalty Program</span>
                </a>
            </li>
        </ul>

        <p class="menu-label">Authorization</p>
        <ul class="menu-list">
            <li>
                <a href="{{ route('roles.index') }}">
                    <span class="icon"><i class="mdi mdi-shield-key"></i></span>
                    <span class="menu-item-label">Role </span>
                </a>
            </li>
            <li>
                <a href="{{ route('permissions.index') }}">
                    <span class="icon"><i class="mdi mdi-account-key"></i></span>
                    <span class="menu-item-label">Permissions</span>
                </a>
            </li>


        </ul>

        <p class="menu-label">Reports</p>
        <ul class="menu-list">
            <li>
                <a href="reports.html">
                    <span class="icon"><i class="mdi mdi-file-chart"></i></span>
                    <span class="menu-item-label">Reports</span>
                </a>
            </li>
            <li>
                <a href="analytics.html">
                    <span class="icon"><i class="mdi mdi-chart-bar"></i></span>
                    <span class="menu-item-label">Analytics</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
