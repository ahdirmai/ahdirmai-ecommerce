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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="name" class="border rounded px-3 py-2 w-full"
                                        placeholder="Full Name" required>
                                    <input type="email" name="email" class="border rounded px-3 py-2 w-full"
                                        placeholder="Email" required>
                                    <input type="text" name="address"
                                        class="border rounded px-3 py-2 w-full md:col-span-2" placeholder="Address"
                                        required>
                                    <input type="text" name="city" class="border rounded px-3 py-2 w-full"
                                        placeholder="City" required>
                                    <input type="text" name="zip" class="border rounded px-3 py-2 w-full"
                                        placeholder="ZIP Code" required>
                                </div>
                            </div>
                            <!-- Order Summary -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Order Summary</h3>
                                <div class="bg-gray-50 rounded p-4">
                                    @foreach ($selectedItems as $item)
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
