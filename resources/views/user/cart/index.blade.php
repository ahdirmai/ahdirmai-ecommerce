<x-app-layout>
    <x-slot name="title">
        {{ __('Cart') }}
    </x-slot>

    <div class="p-12 mt-6">
        <div class="container mx-auto px-4 mb-6">
            <ul class="flex space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-600">Cart</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('profile.show') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Profil
                </a>
            </div>
        </div>
        <!-- Checkout Content -->
        <div class="container mx-auto px-4">
            <div class="flex flex-col justify-center md:flex-row gap-8">
                <form action="{{ route('user.cart.checkout') }}" method="POST">
                    @csrf
                    <div class="cart-items-container p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                <thead class="hidden md:table-header-group">
                                    <tr class="bg-gray-100 text-gray-700">
                                        <th class="px-4 py-3 text-left">
                                            <input type="checkbox" id="selectAll" class="mr-2">
                                            Product / Price
                                        </th>
                                        <th class="px-4 py-3 text-center">Quantity</th>
                                        <th class="px-4 py-3 text-center">Total</th>
                                        <th class="px-4 py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
                                    @forelse ($cartItems as $index => $item)
                                        <tr class="cart-item border-b flex flex-col md:table-row md:flex-row">
                                            <td class="px-4 py-3 md:table-cell align-middle">
                                                <div class="flex items-center gap-4">
                                                    <input type="checkbox" name="selected_items[]"
                                                        value="{{ $item->id }}" class="mr-2 item-checkbox">
                                                    <img src="{{ $item->product->getFirstMediaUrl('*') }}"
                                                        alt="{{ $item->product->name ?? '' }}"
                                                        class="w-16 h-16 rounded object-cover border" />
                                                    <div>
                                                        <h3 class="font-semibold text-gray-900">
                                                            {{ $item->product->name ?? '' }}</h3>
                                                        <p class="text-sm text-gray-500">
                                                            {{ $item->product->category->name ?? 'Category' }}
                                                        </p>
                                                        <span class="text-sm text-gray-700">
                                                            Rp. {{ number_format($item->product->price ?? 0, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center md:table-cell">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button
                                                        class="quantity-btn minus px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                                        data-id="{{ $item->id }}" type="button">-</button>
                                                    <input type="number" value="{{ $item->quantity }}" min="1"
                                                        max="10"
                                                        class="item-quantity w-12 text-center border rounded"
                                                        data-id="{{ $item->id }}" readonly />
                                                    <button
                                                        class="quantity-btn plus px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                                        data-id="{{ $item->id }}" type="button">+</button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center font-semibold text-gray-900 md:table-cell total-price"
                                                data-id="{{ $item->id }}">
                                                Rp.
                                                {{ number_format(($item->product->price ?? 0) * $item->quantity, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center md:table-cell">
                                                <button class="remove-item text-red-500 hover:text-red-700"
                                                    data-id="{{ $item->id }}" type="button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-6 text-gray-500">Your cart is
                                                empty.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Total Price --}}
                        <div class="flex justify-end mt-4">
                            <div class="bg-gray-100 rounded px-6 py-3 text-right">
                                <span class="font-semibold text-gray-700">Total:</span>
                                <span class="font-bold text-lg text-gray-900" id="totalAllPrice">
                                    Rp. {{ number_format($totalPrice ?? 0, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="cart-actions flex flex-col md:flex-row items-center justify-between gap-4 mt-6">
                            <button
                                class="btn btn-primary checkout-cart px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition mt-2 md:mt-0"
                                type="submit">Checkout</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('after-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Quantity minus/plus
                document.querySelector('#cartTableBody').addEventListener('click', function(e) {
                    if (e.target.classList.contains('minus')) {
                        e.preventDefault();
                        handleQuantityChange(e.target, 'decrement');
                    }
                    if (e.target.classList.contains('plus')) {
                        e.preventDefault();
                        handleQuantityChange(e.target, 'increment');
                    }
                    if (e.target.classList.contains('remove-item')) {
                        e.preventDefault();
                        handleRemoveItem(e.target);
                    }
                });

                // Select all checkbox
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.addEventListener('change', function(e) {
                        document.querySelectorAll('.item-checkbox').forEach(cb => {
                            cb.checked = e.target.checked;
                        });
                        updateTotalAllPrice();
                    });
                }

                // Checkbox per item
                document.querySelectorAll('.item-checkbox').forEach(cb => {
                    cb.addEventListener('change', updateTotalAllPrice);
                });

                function handleQuantityChange(btn, action) {
                    const id = btn.getAttribute('data-id');
                    const input = document.querySelector('.item-quantity[data-id="' + id + '"]');
                    let value = parseInt(input.value);
                    const min = parseInt(input.min);
                    const max = parseInt(input.max);

                    if (action === 'decrement' && value > min) {
                        fetch('/cart/decrement/' + id, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.new_quantity !== undefined) {
                                    input.value = data.new_quantity;
                                    updateTotal(id, data.new_quantity);
                                } else {
                                    const row = input.closest('tr');
                                    if (row) row.remove();
                                    updateTotalAllPrice();
                                }
                            })
                            .catch(() => alert('Failed to decrement quantity!'));
                    }

                    if (action === 'increment' && value < max) {
                        fetch('/cart/increment/' + id, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.new_quantity !== undefined) {
                                    input.value = data.new_quantity;
                                    updateTotal(id, data.new_quantity);
                                }
                            })
                            .catch(() => alert('Failed to increment quantity!'));
                    }
                }

                function updateTotal(id, quantity) {
                    const row = document.querySelector('.item-quantity[data-id="' + id + '"]').closest('tr');
                    const priceText = row.querySelector('span.text-sm.text-gray-700').textContent.replace('Rp. ', '')
                        .replace(/,/g, '');
                    const price = parseFloat(priceText);
                    const totalCell = row.querySelector('.total-price');
                    const total = number_format(price * quantity, 2);
                    totalCell.textContent = 'Rp. ' + total;

                    // Update total only if checkbox is checked
                    if (row.querySelector('.item-checkbox').checked) {
                        updateTotalAllPrice();
                    }
                }

                function updateTotalAllPrice() {
                    const checkedItems = document.querySelectorAll('.item-checkbox:checked');
                    let totalAll = 0;

                    checkedItems.forEach(cb => {
                        const row = cb.closest('tr');
                        const totalCell = row.querySelector('.total-price');
                        const priceText = totalCell.textContent.replace('Rp. ', '').replace(/,/g, '');
                        totalAll += parseFloat(priceText);
                    });

                    document.getElementById('totalAllPrice').textContent = 'Rp. ' + number_format(totalAll, 2);
                }

                function number_format(number, decimals) {
                    number = parseFloat(number);
                    if (isNaN(number)) return '0.00';
                    var parts = number.toFixed(decimals).split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return parts.join('.');
                }

                function handleRemoveItem(btn) {
                    const id = btn.getAttribute('data-id');
                    if (confirm('Are you sure you want to remove this item?')) {
                        fetch('/cart/remove/' + id, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status == 'success') {
                                    const row = btn.closest('tr');
                                    if (row) row.remove();
                                    updateTotalAllPrice();
                                } else if (data.error) {
                                    alert(data.error);
                                } else {
                                    alert('Failed to remove item!');
                                }
                            })
                            .catch(() => alert('Failed to remove item!'));
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
