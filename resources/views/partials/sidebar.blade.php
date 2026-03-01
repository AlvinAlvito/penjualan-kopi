<nav>
    <div class="logo-name">
        <span class="logo_name">
            <img width="50px" src="/images/logo.svg" alt=""> Admin
        </span>
    </div>

    <div class="menu-items">
        <ul class="nav-links">

            {{-- ================= ADMIN ================= --}}
            @if (session('is_admin'))

                <li>
                    <a href="{{ route('admin.index') }}"
                       class="{{ Request::is('admin') ? 'active' : '' }}">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.pemohon.index') }}"
                       class="{{ Request::is('admin/pemohon*') ? 'active' : '' }}">
                        <i class="uil uil-users-alt"></i>
                        <span class="link-name">Data Pemohon</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.antrian.index') }}"
                       class="{{ Request::is('admin/antrian*') ? 'active' : '' }}">
                        <i class="uil uil-schedule"></i>
                        <span class="link-name">Data Antrian</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.kuota.index') }}"
                       class="{{ Request::is('admin/kuota*') ? 'active' : '' }}">
                        <i class="uil uil-calendar-alt"></i>
                        <span class="link-name">Kuota Harian</span>
                    </a>
                </li>

            @endif
        </ul>

        {{-- ================= FOOTER ================= --}}
        <ul class="logout-mode">
            @if (session('is_admin'))
                <li>
                    <a href="{{ route('logout') }}">
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Logout</span>
                    </a>
                </li>
            @endif

            <li class="mode">
                <a href="#">
                    <i class="uil uil-moon"></i>
                    <span class="link-name">Dark Mode</span>
                </a>
                <div class="mode-toggle">
                    <span class="switch"></span>
                </div>
            </li>
        </ul>
    </div>
</nav>
