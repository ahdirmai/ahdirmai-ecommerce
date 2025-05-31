<!-- Navigation -->
<nav class="fixed top-0 left-0 w-full bg-white dark:bg-gray-900 shadow dark:shadow-gray-700 z-50 dark-transition">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">DigitalHub</a>

        <ul class="hidden md:flex space-x-6 font-medium">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="text-black dark:text-white hover:text-gray-700 dark:hover:text-gray-300 {{ request()->routeIs('dashboard') ? 'font-semibold' : '' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="#categories" class="hover:text-gray-700 dark:hover:text-gray-300">
                    Categories
                </a>
            </li>
            <li>
                <a href="#featured" class="hover:text-gray-700 dark:hover:text-gray-300">
                    Featured
                </a>
            </li>
            <li>
                <a href="#new-arrivals" class="hover:text-gray-700 dark:hover:text-gray-300">
                    New Arrivals
                </a>
            </li>
            <li>
                <a href="#testimonials" class="hover:text-gray-700 dark:hover:text-gray-300">
                    Testimonials
                </a>
            </li>
        </ul>

        <div class="flex items-center space-x-4">
            <i class="fas fa-search cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
            <i id="darkModeToggle" class="fas fa-moon cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
            <div class="relative">
                <i class="fas fa-shopping-cart cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
                <span
                    class="absolute -top-2 -right-2 bg-black dark:bg-white text-white dark:text-black text-xs px-1 rounded-full"
                    id="cart-count">0</span>
            </div>
            <i id="mobileMenuToggle"
                class="fas fa-bars md:hidden cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu md:hidden bg-white dark:bg-gray-900 border-t dark:border-gray-700">
        <ul class="px-4 py-4 space-y-3 font-medium">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="block text-black dark:text-white hover:text-gray-700 dark:hover:text-gray-300">
                    Home
                </a>
            </li>
            <li>
                <a href="#categories" class="block hover:text-gray-700 dark:hover:text-gray-300">
                    Categories
                </a>
            </li>
            <li>
                <a href="#featured" class="block hover:text-gray-700 dark:hover:text-gray-300">
                    Featured
                </a>
            </li>
            <li>
                <a href="#new-arrivals" class="block hover:text-gray-700 dark:hover:text-gray-300">
                    New Arrivals
                </a>
            </li>
            <li>
                <a href="#testimonials" class="block hover:text-gray-700 dark:hover:text-gray-300">
                    Testimonials
                </a>
            </li>
        </ul>
    </div>
</nav>
