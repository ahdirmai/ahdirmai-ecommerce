<form action="{{ route('user.cart.checkout') }}" method="POST">
    @csrf
    <div class="cart-items-container p-4">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                <thead class="hidden md:table-header-group">
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="mr-2" onclick="toggleSelectAll(this)">
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
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                        class="mr-2 item-checkbox">
                                    <img src="{{ $item->product->getFirstMediaUrl('*') }}"
                                        alt="{{ $item->product->name ?? '' }}"
                                        class="w-16 h-16 rounded object-cover border" />
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $item->product->name ?? '' }}</h3>
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
                                    {{-- <button class="quantity-btn minus px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                        data-id="{{ $item->id }}" onclick="decreaseQuantity({{ $item->id }})"
                                        type="button">-</button> --}}
                                    <input type="number" value="{{ $item->quantity }}" min="1" max="10"
                                        class="item-quantity w-12 text-center border rounded"
                                        data-id="{{ $item->id }}" readonly />
                                    {{-- <button class="quantity-btn plus px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                        data-id="{{ $item->id }}" onclick="increaseQuantity({{ $item->id }})"
                                        type="button">+</button> --}}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-900 md:table-cell total-price"
                                data-id="{{ $item->id }}">
                                Rp. {{ number_format(($item->product->price ?? 0) * $item->quantity, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center md:table-cell">
                                <button class="remove-item text-red-500 hover:text-red-700"
                                    data-id="{{ $item->id }}" type="button"
                                    onclick="removeCartItem({{ $item->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">Your cart is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="cart-actions flex flex-col md:flex-row items-center justify-between gap-4 mt-6">
            {{-- <button
                class="btn btn-outline update-cart px-6 py-2 border border-gray-400 text-gray-700 rounded hover:bg-gray-100 transition mt-2 md:mt-0"
                type="button">Update Cart</button> --}}
            <button
                class="btn btn-primary checkout-cart px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition mt-2 md:mt-0"
                type="submit">Checkout</button>
        </div>
    </div>
</form>

<script></script>
