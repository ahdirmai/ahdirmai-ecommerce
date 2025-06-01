<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Profile') }}
    </x-slot>

    <div class="p-12 mt-6">
        <!-- Breadcrumb -->
        <div class="container mx-auto px-4 mb-6">
            <ul class="flex space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                <li class="text-gray-500">/</li>
                <li><a href="{{ route('profile.show') }}" class="text-blue-600 hover:underline">Profile</a></li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-600">Edit</li>
            </ul>

            {{-- back button --}}
            <div class="mt-4">
                <a href="{{ route('profile.show') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Profil
                </a>
            </div>
        </div>

        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow space-y-10">
                <!-- FORM 1: Update Profile Info -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Informasi Profil</h2>
                    <form action="{{ route('user.profile.update.info') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Avatar -->
                        <div>
                            <label class="block font-medium mb-1">Avatar</label>
                            <div class="flex items-center gap-4">
                                <img id="avatar-preview"
                                    src="{{ Auth::user()->getFirstMediaUrl('avatars') ?: 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff&size=128' }}"
                                    alt="Avatar" class="w-16 h-16 rounded-full object-cover border border-gray-300">
                                <input type="file" name="avatar" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border file:border-gray-300 file:rounded-lg file:bg-white file:text-gray-700 hover:file:bg-gray-100"
                                    onchange="previewAvatar(event)" />
                            </div>
                            @push('after-scripts')
                                <script>
                                    function previewAvatar(event) {
                                        const input = event.target;
                                        const preview = document.getElementById('avatar-preview');
                                        if (input.files && input.files[0]) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                preview.src = e.target.result;
                                            }
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }
                                </script>
                            @endpush
                            @error('avatar')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block font-medium mb-1">Nama</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', Auth::user()->name) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
                                required>
                            @error('name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>


                <!-- FORM 2: Change Password -->
                <div class="border-t pt-8">
                    <h2 class="text-xl font-bold mb-4">Ubah Password</h2>
                    <form action="{{ route('user.profile.update.password') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Password Lama -->
                        <div>
                            <label for="current_password" class="block font-medium mb-1">Password Lama</label>
                            <input type="password" name="current_password" id="current_password"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
                                required>
                            @error('current_password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <label for="password" class="block font-medium mb-1">Password Baru</label>
                            <input type="password" name="password" id="password"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
                                required>
                            @error('password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi -->
                        <div>
                            <label for="password_confirmation" class="block font-medium mb-1">Konfirmasi Password
                                Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
                                required>
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Ubah
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
