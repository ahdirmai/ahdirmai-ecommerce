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
                                        {{-- email --}}
                                        {{-- @if (!empty($selectedAddress['email'])) --}}
                                        <div>{{ Auth::user()->email }}</div>
                                        {{-- @endif --}}
                                    </div>
                                @endif

                                @if (count($shippingAddress) === 0)
                                    <label for="shipping_address_id"
                                        class="block mb-2 text-sm font-medium text-gray-700">Pilih Alamat
                                        Pengiriman</label>
                                    <select name="shipping_address_id" id="shipping_address_id"
                                        class="border rounded px-3 py-2 w-full mb-2" required>
                                        <option value="" disabled selected>Pilih alamat pengiriman</option>
                                        <option value="buat_baru">+ Buat Alamat Baru</option>
                                    </select>
                                @else
                                    <label for="shipping_address_id"
                                        class="block mb-2 text-sm font-medium text-gray-700">Pilih Alamat
                                        Pengiriman</label>
                                    <select name="shipping_address_id" id="shipping_address_id"
                                        class="border rounded px-3 py-2 w-full mb-2" required>
                                        @foreach ($shippingAddress as $address)
                                            <option value="{{ $address['id'] }}"
                                                data-label="{{ $address['label'] ?? 'Alamat' }}"
                                                data-address_line1="{{ $address['address_line1'] }}"
                                                data-address_line2="{{ $address['address_line2'] ?? '' }}"
                                                data-city="{{ $address['city'] }}"
                                                data-state="{{ $address['state'] }}"
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
                                        <option value="buat_baru">+ Buat Alamat Baru</option>
                                    </select>
                                @endif

                                <!-- Modal -->
                                @push('modal-section')
                                    <div id="modal-address"
                                        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                                            <button type="button" id="close-modal-address"
                                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                                            <h3 class="text-lg font-semibold mb-4">Buat Alamat Baru</h3>
                                            <form id="address-form"
                                                action="{{ route('user.address.store-new-from-checkout', ['redirect' => request()->fullUrl()]) }}"
                                                method="POST" class="space-y-6">
                                                @csrf
                                                <input type="hidden" name="address_id" id="address_id" value="">
                                                <div>
                                                    <label for="label" class="block text-sm font-medium">Label Alamat
                                                        (opsional)</label>
                                                    <input type="text" name="label" id="label"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('label') }}">
                                                </div>
                                                <div>
                                                    <label for="address_line1" class="block text-sm font-medium">Alamat
                                                        1</label>
                                                    <input type="text" name="address_line1" id="address_line1"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('address_line1') }}" required>
                                                </div>
                                                <div>
                                                    <label for="address_line2" class="block text-sm font-medium">Alamat 2
                                                        (opsional)</label>
                                                    <input type="text" name="address_line2" id="address_line2"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('address_line2') }}">
                                                </div>
                                                <div>
                                                    <label for="city" class="block text-sm font-medium">Kota</label>
                                                    <input type="text" name="city" id="city"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('city') }}" required>
                                                </div>
                                                <div>
                                                    <label for="state" class="block text-sm font-medium">Provinsi</label>
                                                    <input type="text" name="state" id="state"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('state') }}" required>
                                                </div>
                                                <div>
                                                    <label for="postal_code" class="block text-sm font-medium">Kode
                                                        Pos</label>
                                                    <input type="text" name="postal_code" id="postal_code"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('postal_code') }}" required>
                                                </div>
                                                <div>
                                                    <label for="country" class="block text-sm font-medium">Negara</label>
                                                    <input type="text" name="country" id="country"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('country') }}" required>
                                                </div>
                                                <div>
                                                    <label for="phone_number" class="block text-sm font-medium">No.
                                                        Telepon (opsional)</label>
                                                    <input type="text" name="phone_number" id="phone_number"
                                                        class="mt-1 block w-full border rounded"
                                                        value="{{ old('phone_number') }}">
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <input type="checkbox" name="is_default" id="is_default"
                                                        value="1" {{ old('is_default') ? 'checked' : '' }}>
                                                    <label for="is_default" class="text-sm">Jadikan sebagai alamat
                                                        utama</label>
                                                </div>
                                                <div>
                                                    <button type="submit" form="address-form"
                                                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan
                                                        Alamat</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endpush


                                @push('after-scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const select = document.getElementById('shipping_address_id');
                                            const infoDiv = document.getElementById('selected-address-info');
                                            const modal = document.getElementById('modal-address');
                                            const closeModalBtn = document.getElementById('close-modal-address');

                                            if (select) {
                                                select.addEventListener('change', function() {
                                                    if (select.value === 'buat_baru') {
                                                        modal.classList.remove('hidden');
                                                        select.value = '';
                                                        if (infoDiv) infoDiv.innerHTML = '';
                                                        return;
                                                    }
                                                    @if (count($shippingAddress) > 0)
                                                        const selected = select.options[select.selectedIndex];
                                                        if (infoDiv && selected && selected.value !== 'buat_baru') {
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
                                                        }
                                                    @endif
                                                });
                                            }

                                            if (closeModalBtn && modal) {
                                                closeModalBtn.addEventListener('click', function() {
                                                    modal.classList.add('hidden');
                                                });
                                            }

                                            // Optional: close modal on outside click
                                            if (modal) {
                                                modal.addEventListener('click', function(e) {
                                                    if (e.target === modal) {
                                                        modal.classList.add('hidden');
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                @endpush

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
