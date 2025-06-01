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
                <a href="{{ request()->routeIs('dashboard') ? '#featured' : route('products.index') }}"
                    class="hover:text-gray-700 dark:hover:text-gray-300">
                    Product
                </a>
            </li>


        </ul>

        <div class="flex items-center space-x-4">
            <i class="fas fa-search cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
            <i id="darkModeToggle" class="fas fa-moon cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
            @guest
                <div class="relative">
                    <button id="userDropdownGuest" class="focus:outline-none">
                        <i class="fas fa-user cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
                    </button>
                    <div id="guestDropdownMenu"
                        class="hidden absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded shadow-lg z-50">
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 text-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Login</a>
                    </div>
                </div>
            @else
                <div class="relative">
                    <i class="fas fa-shopping-cart cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-black dark:bg-white text-white dark:text-black text-xs px-1 rounded-full"
                        id="cart-count">0</span>
                </div>
                <div class="relative">
                    <button id="userDropdownAuth" class="focus:outline-none">
                        <i class="fas fa-user-circle cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i>
                    </button>
                    <div id="authDropdownMenu"
                        class="hidden absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded shadow-lg z-50">
                        <a href="{{ route('profile.show') }}"
                            class="block px-4 py-2 text-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                        </form>
                    </div>
                </div>
            @endguest

            <script>
                // Dropdown for guest
                document.addEventListener('DOMContentLoaded', function() {
                    const guestBtn = document.getElementById('userDropdownGuest');
                    const guestMenu = document.getElementById('guestDropdownMenu');
                    if (guestBtn && guestMenu) {
                        guestBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            guestMenu.classList.toggle('hidden');
                        });
                        document.addEventListener('click', function() {
                            guestMenu.classList.add('hidden');
                        });
                    }

                    // Dropdown for auth
                    const authBtn = document.getElementById('userDropdownAuth');
                    const authMenu = document.getElementById('authDropdownMenu');
                    if (authBtn && authMenu) {
                        authBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            authMenu.classList.toggle('hidden');
                        });
                        document.addEventListener('click', function() {
                            authMenu.classList.add('hidden');
                        });
                    }
                });
            </script>

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
                    Product
                </a>
            </li>


        </ul>
    </div>
</nav>
