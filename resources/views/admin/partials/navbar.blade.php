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
            <div class="navbar-item dropdown has-divider has-user-avatar">
                <a class="navbar-link">

                    <div class="is-user-name"><span>{{ auth()->user()->name }}</span></div>
                    <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
                </a>
                <div class="navbar-dropdown">
                    <a href="{{ route('profile.edit') }}" class="navbar-item">
                        <span class="icon"><i class="mdi mdi-account"></i></span>
                        <span>Profil Saya</span>
                    </a>
                    <hr class="navbar-divider">
                    <!-- Form Logout Breeze -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="navbar-item"
                            style="background: none; border: none; cursor: pointer;">
                            <span class="icon"><i class="mdi mdi-logout"></i></span>
                            <span>Keluar</span>
                        </button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</nav>
