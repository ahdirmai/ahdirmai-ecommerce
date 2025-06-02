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
            {{-- <i class="fas fa-search cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i> --}}
            {{-- <i id="darkModeToggle" class="fas fa-moon cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"></i> --}}
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
                    <i class="fas fa-shopping-cart cursor-pointer hover:text-gray-700 dark:hover:text-gray-300"
                        id="cartModalToggle"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-black dark:bg-white text-white dark:text-black text-xs px-1 rounded-full"
                        id="cart-count">0</span>
                </div>

                <!-- Cart Modal (slide in from right) -->
                @push('modal-section')
                    <div id="cartModal"
                        class="fixed top-0 right-0 h-full w-150 bg-white dark:bg-gray-900 shadow-lg z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
                        <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                            <h2 class="text-lg font-semibold">Your Cart</h2>
                            <button id="closeCartModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4" id="cartModalContent">
                            <!-- Cart content goes here -->
                            <p class="text-gray-500">Loading cart...</p>
                        </div>
                    </div>
                @endpush
                @push('after-scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const cartToggle = document.getElementById('cartModalToggle');
                            const cartModal = document.getElementById('cartModal');
                            const closeCart = document.getElementById('closeCartModal');
                            const cartContent = document.getElementById('cartModalContent');

                            cartToggle.addEventListener('click', function(e) {
                                e.stopPropagation();
                                cartModal.classList.remove('translate-x-full');
                                // Fetch cart content via AJAX
                                fetch('{{ route('user.modal.cart') }}')
                                    .then(response => response.text())
                                    .then(html => {
                                        cartContent.innerHTML = html;
                                    })
                                    .catch(() => {
                                        cartContent.innerHTML = '<p class="text-red-500">Failed to load cart.</p>';
                                    });
                            });

                            closeCart.addEventListener('click', function() {
                                cartModal.classList.add('translate-x-full');
                            });

                            // Close modal when clicking outside
                            document.addEventListener('click', function(event) {
                                if (!cartModal.contains(event.target) && event.target !== cartToggle) {
                                    cartModal.classList.add('translate-x-full');
                                }
                            });

                            // Prevent modal from closing when clicking inside
                            cartModal.addEventListener('click', function(event) {
                                event.stopPropagation();
                            });
                        });
                    </script>

                    <script>
                        function decreaseQuantity(id) {
                            const input = document.querySelector('.item-quantity[data-id="' + id + '"]');
                            let value = parseInt(input.value);
                            if (value > parseInt(input.min)) {
                                // AJAX call to decrement
                                fetch('/cart/decrement/' + id, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.new_quantity !== undefined) {
                                            value = data.new_quantity;
                                            input.value = value;
                                            updateTotal(id, value);
                                        } else {
                                            // Optionally handle item removal from DOM if deleted
                                            if (input.closest('tr')) input.closest('tr').remove();
                                        }
                                    })
                                    .catch(() => {
                                        alert('Failed to decrement quantity!');
                                    });
                            }
                        }

                        function increaseQuantity(id) {
                            const input = document.querySelector('.item-quantity[data-id="' + id + '"]');
                            let value = parseInt(input.value);
                            if (value < parseInt(input.max)) {
                                // AJAX call to increment
                                fetch('/cart/increment/' + id, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.new_quantity !== undefined) {
                                            value = data.new_quantity;
                                            input.value = value;
                                            updateTotal(id, value);
                                        }
                                    })
                                    .catch(() => {
                                        alert('Failed to increment quantity!');
                                    });
                            }
                        }

                        function updateTotal(id, quantity) {
                            const row = document.querySelector('.item-quantity[data-id="' + id + '"]').closest('tr');
                            const priceText = row.querySelector('span.text-sm.text-gray-700').textContent.replace('Rp. ', '').replace(/,/g,
                                '');
                            const price = parseFloat(priceText);
                            const totalCell = row.querySelector('.total-price');
                            const total = (price * quantity).toLocaleString('id-ID', {
                                minimumFractionDigits: 2
                            });
                            totalCell.textContent = 'Rp. ' + total;
                        }

                        function toggleSelectAll(source) {
                            const checkboxes = document.querySelectorAll('.item-checkbox');
                            checkboxes.forEach(cb => cb.checked = source.checked);
                        }
                        // handle removeCartItem()
                        function removeCartItem(id) {
                            if (confirm('Are you sure you want to remove this item?')) {
                                fetch('/cart/remove/' + id, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status == 'success') {
                                            // Find the tr element containing the remove button with the correct data-id
                                            const removeBtn = document.querySelector('.remove-item[data-id="' + id + '"]');
                                            if (removeBtn) {
                                                const row = removeBtn.closest('tr');
                                                if (row) row.remove();
                                            }
                                            // Optionally update cart count or show a toast
                                        } else if (data.error) {
                                            alert(data.error);
                                        } else {
                                            alert('Failed to remove item!');
                                        }
                                    })
                                    .catch((error) => {
                                        alert('Failed to remove item!');
                                    });
                            }
                        }
                    </script>
                @endpush



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
