<aside class="left-sidebar">

    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard.index') }}" class="text-nowrap logo-img">
                {{--  --}}
                <span style="font-weight: bold; color: #000; font-size: 2rem;">DigitalHub</span>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard.index') }}" aria-expanded="false">
                        <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                        href="{{ route('admin.categories.index') }}" aria-expanded="false">
                        <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                        <span class="hide-menu">Category</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                        href="{{ route('admin.products.index') }}" aria-expanded="false">
                        <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                        <span class="hide-menu">Products</span>
                    </a>
                </li>

                {{-- order --}}
                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                        href="{{ route('admin.orders.index') }}" aria-expanded="false">
                        <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                        <span class="hide-menu">Orders</span>
                    </a>
                </li>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
</aside>
