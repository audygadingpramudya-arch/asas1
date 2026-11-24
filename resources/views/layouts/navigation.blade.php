<nav x-data="{ sidebarOpen: window.innerWidth >= 1024, wasDesktop: window.innerWidth >= 1024 }"
     :class="{ 'sidebar-collapsed': !sidebarOpen }"
     @keydown.escape.window="sidebarOpen = false"
     @resize.window="if ((window.innerWidth >= 1024) !== wasDesktop) { wasDesktop = window.innerWidth >= 1024; sidebarOpen = wasDesktop }"
     class="staff-navigation">
    <header class="staff-navbar">
        <div class="staff-navbar-start">
            <button type="button" class="menu-toggle" @click="sidebarOpen = ! sidebarOpen"
                    :aria-expanded="sidebarOpen.toString()" aria-controls="staff-sidebar" aria-label="Tampilkan atau sembunyikan menu">
                <span :class="{ 'menu-line-first-open': sidebarOpen }"></span>
                <span :class="{ 'menu-line-middle-open': sidebarOpen }"></span>
                <span :class="{ 'menu-line-last-open': sidebarOpen }"></span>
            </button>
            <a href="{{ route('dashboard') }}" class="staff-brand">
                <x-application-logo class="staff-brand-logo" />
                <span class="staff-brand-name">Laundry<span>Kami</span></span>
            </a>
            <label class="navbar-search" for="staff-search">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.7"/>
                    <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
                <input id="staff-search" type="search" data-table-search data-table-global-search
                       placeholder="Cari transaksi..." autocomplete="off">
                <kbd>Ctrl K</kbd>
            </label>
        </div>

        <div class="staff-navbar-end">
            @if (request()->routeIs('dashboard'))
                <time class="navbar-datetime" data-live-clock></time>
                <a href="{{ route('transaksi.index') }}" class="navbar-notification" aria-label="Lihat daftar transaksi">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9ZM10 21h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @if (isset($transaksiProses) && $transaksiProses > 0)
                        <span>{{ $transaksiProses > 9 ? '9+' : $transaksiProses }}</span>
                    @endif
                </a>
            @endif
            <span class="staff-role-badge">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</span>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="staff-profile-button" aria-label="Buka menu akun">
                        <span class="staff-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span class="staff-profile-name">{{ Auth::user()->name }}</span>
                        <svg class="staff-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.09 1.032l-4.25 4.5a.75.75 0 0 1-1.09 0l-4.25-4.5a.75.75 0 0 1 .02-1.052Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                         onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </header>

    <div x-cloak x-show="sidebarOpen" class="sidebar-backdrop"
         @click="sidebarOpen = false"
         x-transition:enter="sidebar-backdrop-enter"
         x-transition:enter-start="sidebar-backdrop-enter-start"
         x-transition:enter-end="sidebar-backdrop-enter-end"
         x-transition:leave="sidebar-backdrop-leave"
         x-transition:leave-start="sidebar-backdrop-leave-start"
         x-transition:leave-end="sidebar-backdrop-leave-end"></div>

    <aside id="staff-sidebar" x-cloak x-show="sidebarOpen" class="staff-sidebar"
           aria-label="Menu navigasi"
           x-transition:enter="sidebar-enter"
           x-transition:enter-start="sidebar-enter-start"
           x-transition:enter-end="sidebar-enter-end"
           x-transition:leave="sidebar-leave"
           x-transition:leave-start="sidebar-leave-start"
           x-transition:leave-end="sidebar-leave-end">
        <div class="sidebar-heading">
            <div>
                <span class="sidebar-eyebrow">LAUNDRYKAMI</span>
                <h2>Workspace</h2>
            </div>
            <button type="button" class="sidebar-close" @click="sidebarOpen = false" aria-label="Tutup menu">&times;</button>
        </div>

        <p class="sidebar-section-label">WORKSPACE</p>
        <div class="sidebar-links">
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
                <span class="sidebar-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="4" y="4" width="6.5" height="6.5" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                        <rect x="13.5" y="4" width="6.5" height="6.5" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                        <rect x="4" y="13.5" width="6.5" height="6.5" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                        <rect x="13.5" y="13.5" width="6.5" height="6.5" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </span>
                <span>Dashboard</span>
                <span class="sidebar-link-arrow" aria-hidden="true">&rarr;</span>
            </a>

            <a href="{{ route('layanan.index') }}" @click="sidebarOpen = false"
               class="sidebar-link {{ request()->routeIs('layanan.*') ? 'sidebar-link-active' : '' }}">
                <span class="sidebar-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 4.5h10A1.5 1.5 0 0 1 18.5 6v12a1.5 1.5 0 0 1-1.5 1.5H7A1.5 1.5 0 0 1 5.5 18V6A1.5 1.5 0 0 1 7 4.5Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8.5 9h7M8.5 12.5h7M8.5 16h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>Data Layanan</span>
                <span class="sidebar-link-arrow" aria-hidden="true">&rarr;</span>
            </a>

            <a href="{{ route('transaksi.index') }}" @click="sidebarOpen = false"
               class="sidebar-link {{ request()->routeIs('transaksi.*') ? 'sidebar-link-active' : '' }}">
                <span class="sidebar-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 4.5h10A1.5 1.5 0 0 1 18.5 6v14l-2.2-1.5L14 20l-2-1.5L10 20l-2.2-1.5L5.5 20V6A1.5 1.5 0 0 1 7 4.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M8.5 9h7M8.5 12.5h7M8.5 16h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>Transaksi Laundry</span>
                <span class="sidebar-link-arrow" aria-hidden="true">&rarr;</span>
            </a>

            <a href="{{ route('laporan.index') }}" @click="sidebarOpen = false"
               class="sidebar-link {{ request()->routeIs('laporan.*') ? 'sidebar-link-active' : '' }}">
                <span class="sidebar-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 19.5h14M7.5 16v-5M12 16V6.5M16.5 16V9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span>Rekap Transaksi</span>
                <span class="sidebar-link-arrow" aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
            @csrf
            <button type="submit" class="sidebar-link sidebar-logout">
                <span class="sidebar-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M10 5H6.5A1.5 1.5 0 0 0 5 6.5v11A1.5 1.5 0 0 0 6.5 19H10M14 8l4 4-4 4M18 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span>Logout</span>
            </button>
        </form>

        <div class="sidebar-footer">
            <span class="sidebar-footer-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            <span class="sidebar-footer-copy">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</small>
            </span>
        </div>
    </aside>
</nav>
