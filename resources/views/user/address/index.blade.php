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
                <li class="text-gray-600">Alamat</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('profile.show') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Profil
                </a>
            </div>
        </div>

        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow space-y-10">
                <div>
                    <h2 class="text-xl font-bold mb-4">Daftar Alamat</h2>
                    <!-- FORM: Create/Edit Address -->
                    <form id="address-form" action="{{ route('user.address.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="address_id" id="address_id" value="">
                        <div>
                            <label for="label" class="block text-sm font-medium">Label Alamat (opsional)</label>
                            <input type="text" name="label" id="label" class="mt-1 block w-full border rounded"
                                value="{{ old('label') }}">
                        </div>
                        <div>
                            <label for="address_line1" class="block text-sm font-medium">Alamat 1</label>
                            <input type="text" name="address_line1" id="address_line1"
                                class="mt-1 block w-full border rounded" value="{{ old('address_line1') }}" required>
                        </div>
                        <div>
                            <label for="address_line2" class="block text-sm font-medium">Alamat 2 (opsional)</label>
                            <input type="text" name="address_line2" id="address_line2"
                                class="mt-1 block w-full border rounded" value="{{ old('address_line2') }}">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium">Kota</label>
                            <input type="text" name="city" id="city" class="mt-1 block w-full border rounded"
                                value="{{ old('city') }}" required>
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium">Provinsi</label>
                            <input type="text" name="state" id="state" class="mt-1 block w-full border rounded"
                                value="{{ old('state') }}" required>
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium">Kode Pos</label>
                            <input type="text" name="postal_code" id="postal_code"
                                class="mt-1 block w-full border rounded" value="{{ old('postal_code') }}" required>
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium">Negara</label>
                            <input type="text" name="country" id="country" class="mt-1 block w-full border rounded"
                                value="{{ old('country') }}" required>
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium">No. Telepon (opsional)</label>
                            <input type="text" name="phone_number" id="phone_number"
                                class="mt-1 block w-full border rounded" value="{{ old('phone_number') }}">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="is_default" id="is_default" value="1"
                                {{ old('is_default') ? 'checked' : '' }}>
                            <label for="is_default" class="text-sm">Jadikan sebagai alamat utama</label>
                        </div>

                    </form>
                    <div class="flex space-x-2">
                        <button type="submit" id="submit-btn" form="address-form"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Simpan
                            Alamat</button>
                        <button type="button" id="cancel-edit"
                            class="hidden px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Batal
                            Edit</button>
                    </div>
                </div>

                <!-- Address List -->
                <div>
                    @forelse($addresses as $address)
                        <div class="border p-4 rounded mt-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-bold">{{ $address->label ?? 'Alamat' }}</div>
                                    <div>{{ $address->address_line1 }}</div>
                                    @if ($address->address_line2)
                                        <div>{{ $address->address_line2 }}</div>
                                    @endif
                                    <div>{{ $address->city }}, {{ $address->state }}, {{ $address->postal_code }}
                                    </div>
                                    <div>{{ $address->country }}</div>
                                    @if ($address->phone_number)
                                        <div>Telp: {{ $address->phone_number }}</div>
                                    @endif
                                    @if ($address->is_default)
                                        <span
                                            class="inline-block px-2 py-1 bg-blue-200 text-blue-800 text-xs rounded">Utama</span>
                                    @endif
                                    @if (!$address->is_active)
                                        <span
                                            class="inline-block px-2 py-1 bg-gray-200 text-gray-800 text-xs rounded">Tidak
                                            Aktif</span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                                    @if (!$address->is_default)
                                        <form action="{{ route('user.address.set_default', $address->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600 transition">Jadikan
                                                Utama</button>
                                        </form>
                                    @endif
                                    @if ($address->is_active)
                                        <form action="{{ route('user.address.set_inactive', $address->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600 transition">Nonaktifkan</button>
                                        </form>
                                    @else
                                        <form action="{{ route('user.address.set_active', $address->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600 transition">Aktifkan</button>
                                        </form>
                                    @endif
                                    <button type="button"
                                        class="edit-btn px-3 py-1 text-xs bg-yellow-400 text-white rounded hover:bg-yellow-500 transition"
                                        data-address='@json($address)'
                                        data-route='{{ route('user.address.update', $address) }}'>Edit</button>
                                    <form action="{{ route('user.address.delete', $address->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500 mt-6">Belum ada alamat yang ditambahkan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('after-scripts')
        <script>
            // Handle Edit Button
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.dataset.address);
                    const route = this.dataset.route;

                    // set form route
                    document.getElementById('address-form').action = route;

                    //add form method value put
                    const form = document.getElementById('address-form');
                    const methodInput = document.createElement('input');
                    methodInput.setAttribute('type', 'hidden');
                    methodInput.setAttribute('name', '_method');
                    methodInput.setAttribute('value', 'PUT');
                    form.appendChild(methodInput);
                    document.getElementById('address_id').value = data.id;
                    document.getElementById('label').value = data.label ?? '';
                    document.getElementById('address_line1').value = data.address_line1 ?? '';
                    document.getElementById('address_line2').value = data.address_line2 ?? '';
                    document.getElementById('city').value = data.city ?? '';
                    document.getElementById('state').value = data.state ?? '';
                    document.getElementById('postal_code').value = data.postal_code ?? '';
                    document.getElementById('country').value = data.country ?? '';
                    document.getElementById('phone_number').value = data.phone_number ?? '';
                    document.getElementById('is_default').checked = !!data.is_default;
                    document.getElementById('submit-btn').textContent = 'Update Alamat';
                    document.getElementById('cancel-edit').classList.remove('hidden');
                });
            });

            // Handle Cancel Edit
            document.getElementById('cancel-edit').addEventListener('click', function() {
                document.getElementById('address_id').value = '';
                document.getElementById('address-form').action = "{{ route('user.address.store') }}";
                document.getElementById('submit-btn').textContent = 'Simpan Alamat';
                const methodInput = document.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }
                this.classList.add('hidden');
                // Clear form fields
                ['label', 'address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country', 'phone_number']
                .forEach(id => {
                    document.getElementById(id).value = '';
                });
                document.getElementById('is_default').checked = false;
            });
        </script>
    @endpush
</x-app-layout>
