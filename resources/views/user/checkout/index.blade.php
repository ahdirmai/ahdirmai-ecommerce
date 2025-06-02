<x-app-layout>
    <x-slot name="title">
        {{ __('Checkout') }}
    </x-slot>

    <section class="min-h-screen w-full flex items-center justify-center pt-12 pb-12 px-4">
        <div class="max-w-2xl mx-auto w-full">
            <div class="pt-8 px-4">
                <div class="bg-gray-100 py-2">
                    <div class="container mx-auto px-4">
                        <ul class="flex space-x-2 text-sm">
                            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                            <li class="text-gray-500">/</li>
                            <li><a href="{{ route('user.cart.index') }}" class="text-blue-600 hover:underline">Cart</a>
                            </li>
                            <li class="text-gray-500">/</li>
                            <li class="text-gray-600">Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Checkout Content -->
            <section class="py-8">
                <div class="container mx-auto px-4">
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold mb-6">Checkout</h2>
                        <form action="{{ route('user.checkout.process') }}" method="POST">
                            @csrf
                            <!-- Shipping Information -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Shipping Information</h3>
                                @php
                                    // Pilih alamat default & aktif
                                    $selectedAddress = collect($shippingAddress)
                                        ->where('is_active', 1)
                                        ->sortByDesc('is_default')
                                        ->first();
                                @endphp

                                @if ($selectedAddress)
                                    <div class="border p-4 rounded mb-4" id="selected-address-info">
                                        <div class="font-bold">{{ $selectedAddress['label'] ?? 'Alamat' }}</div>
                                        <div>{{ $selectedAddress['address_line1'] }}</div>
                                        @if (!empty($selectedAddress['address_line2']))
                                            <div>{{ $selectedAddress['address_line2'] }}</div>
                                        @endif
                                        <div>{{ $selectedAddress['city'] }}, {{ $selectedAddress['state'] }},
                                            {{ $selectedAddress['postal_code'] }}</div>
                                        <div>{{ $selectedAddress['country'] }}</div>
                                        @if (!empty($selectedAddress['phone_number']))
                                            <div>Telp: {{ $selectedAddress['phone_number'] }}</div>
                                        @endif
                                        @if ($selectedAddress['is_default'])
                                            <span
                                                class="inline-block px-2 py-1 bg-blue-200 text-blue-800 text-xs rounded">Utama</span>
                                        @endif
                                        @if (!$selectedAddress['is_active'])
                                            <span
                                                class="inline-block px-2 py-1 bg-gray-200 text-gray-800 text-xs rounded">Tidak
                                                Aktif</span>
                                        @endif
                                    </div>
                                @endif

                                <label for="shipping_address_id"
                                    class="block mb-2 text-sm font-medium text-gray-700">Pilih Alamat Pengiriman</label>
                                <select name="shipping_address_id" id="shipping_address_id"
                                    class="border rounded px-3 py-2 w-full mb-2" required>
                                    @foreach ($shippingAddress as $address)
                                        <option value="{{ $address['id'] }}"
                                            data-label="{{ $address['label'] ?? 'Alamat' }}"
                                            data-address_line1="{{ $address['address_line1'] }}"
                                            data-address_line2="{{ $address['address_line2'] ?? '' }}"
                                            data-city="{{ $address['city'] }}" data-state="{{ $address['state'] }}"
                                            data-postal_code="{{ $address['postal_code'] }}"
                                            data-country="{{ $address['country'] }}"
                                            data-phone_number="{{ $address['phone_number'] ?? '' }}"
                                            data-is_default="{{ $address['is_default'] }}"
                                            data-is_active="{{ $address['is_active'] }}"
                                            @if ($selectedAddress && $selectedAddress['id'] == $address['id']) selected @endif>
                                            {{ $address['label'] ?? 'Alamat' }} - {{ $address['address_line1'] }},
                                            {{ $address['city'] }}
                                            @if ($address['is_default'])
                                                [Utama]
                                            @endif
                                            @if (!$address['is_active'])
                                                [Tidak Aktif]
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const select = document.getElementById('shipping_address_id');
                                        const infoDiv = document.getElementById('selected-address-info');
                                        if (!select || !infoDiv) return;

                                        select.addEventListener('change', function() {
                                            const selected = select.options[select.selectedIndex];
                                            let html = `<div class="font-bold">${selected.dataset.label}</div>`;
                                            html += `<div>${selected.dataset.address_line1}</div>`;
                                            if (selected.dataset.address_line2) {
                                                html += `<div>${selected.dataset.address_line2}</div>`;
                                            }
                                            html +=
                                                `<div>${selected.dataset.city}, ${selected.dataset.state}, ${selected.dataset.postal_code}</div>`;
                                            html += `<div>${selected.dataset.country}</div>`;
                                            if (selected.dataset.phone_number) {
                                                html += `<div>Telp: ${selected.dataset.phone_number}</div>`;
                                            }
                                            if (selected.dataset.is_default == "1") {
                                                html +=
                                                    `<span class="inline-block px-2 py-1 bg-blue-200 text-blue-800 text-xs rounded">Utama</span>`;
                                            }
                                            if (selected.dataset.is_active == "0") {
                                                html +=
                                                    `<span class="inline-block px-2 py-1 bg-gray-200 text-gray-800 text-xs rounded">Tidak Aktif</span>`;
                                            }
                                            infoDiv.innerHTML = html;
                                        });
                                    });
                                </script>
                            </div>
                            <!-- Order Summary -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Order Summary</h3>
                                <div class="bg-gray-50 rounded p-4">
                                    @foreach ($selectedItems as $item)
                                        <input type="hidden" name="cart_items[]" value="{{ $item->id }}" />

                                        <div class="flex justify-between items-center mb-2">
                                            <span class="flex items-center space-x-2">
                                                <img src="{{ $item->product->getFirstMediaUrl('*') }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="w-10 h-10 object-cover rounded">
                                                <span>{{ $item->product->name }}</span>
                                                <span class="text-gray-500 text-sm">Rp.
                                                    {{ number_format($item->product->price, 2) }}</span>
                                            </span>
                                            <span class="quantity"
                                                data-id="{{ $item->id }}">{{ $item->quantity }}</span>
                                            <span>Rp.
                                                {{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between font-bold border-t pt-2 mt-2">
                                        <span>Total</span>
                                        <span>Rp. {{ number_format($totalPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Method Payment --}}
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Payment Method</h3>
                                <div class="flex space-x-4">
                                    <select name="payment_method" class="border rounded px-3 py-2 w-full" required>
                                        {{-- <option value="credit_card" selected>Credit Card</option> --}}
                                        <option value="transfer">Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Payment Button -->
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700 transition">Place
                                Order</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</x-app-layout>
