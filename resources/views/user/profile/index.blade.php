<x-app-layout>
    <x-slot name="title">
        {{ __('Profile') }}
    </x-slot>

    <div class="p-12 mt-6">
        <!-- Breadcrumb -->
        <div class="container mx-auto px-4 mb-6">
            <ul class="flex space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-600">Profile</li>
            </ul>
        </div>

        <div class="container mx-auto px-4">
            <div class="flex flex-col justify-center md:flex-row gap-8">
                <!-- Profile Info -->
                <div class="md:w-1/3 flex flex-col items-center bg-white p-8 rounded-lg shadow">
                    <div class="mb-4">
                        <img id="avatar-preview"
                            src="{{ Auth::user()->getFirstMediaUrl('avatars') ?: 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff&size=128' }}"
                            alt="Avatar" class="w-32 h-32 rounded-full object-cover border border-gray-300">
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold mb-1">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600 mb-4">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="flex flex-col w-full gap-3 mt-6">
                        <a href="{{ route('user.profile.edit') }}"
                            class="w-full border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium flex items-center justify-center transition">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profile
                        </a>
                        <a href="{{ route('user.address.index') }}"
                            class="w-full border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium flex items-center justify-center transition">
                            <i class="fas fa-map-marker-alt mr-2"></i> Alamat Saya
                        </a>
                        <a {{-- href="{{ route('user.cart.index') }}" --}}
                            class="w-full border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium flex items-center justify-center transition">
                            <i class="fas fa-shopping-cart mr-2"></i> Cart
                        </a>
                        <a {{-- href="{{ route('user.orders.index') }}" --}}
                            class="w-full border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium flex items-center justify-center transition">
                            <i class="fas fa-box mr-2"></i> Pesanan Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
