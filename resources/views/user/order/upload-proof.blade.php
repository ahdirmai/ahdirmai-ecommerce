<x-app-layout>
    <x-slot name="title">
        {{ __('Upload Bukti Transfer') }}
    </x-slot>

    <section class="min-h-screen w-full flex items-center justify-center pt-12 pb-12 px-4">
        <div class="max-w-2xl mx-auto w-full">
            <div class="pt-8 px-4">
                <div class="bg-gray-100 py-2">
                    <div class="container mx-auto px-4">
                        <ul class="flex space-x-2 text-sm">
                            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                            <li class="text-gray-500">/</li>
                            <li><a href="{{ route('user.order.index') }}"
                                    class="text-blue-600 hover:underline">Order</a>
                            </li>
                            <li class="text-gray-500">/</li>
                            <li class="text-gray-600">Upload Bukti Transfer</li>
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('user.order.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Order Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Upload Bukti Transfer Content -->
            <section class="py-8">
                <div class="container mx-auto px-4">
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold mb-6">Upload Bukti Transfer</h2>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Nominal yang harus dibayar</h3>
                            <div class="bg-gray-50 rounded p-4 flex items-center justify-between">
                                <span class="font-bold text-lg">Rp.
                                    {{ number_format($order->payment->amount - $order->payment->amount_paid ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Tujuan Transfer</h3>
                            <div class="bg-gray-50 rounded p-4">
                                @foreach ($bankAccounts as $bank)
                                    <div class="mb-3">
                                        <div class="font-bold">{{ $bank['bank_name'] }}</div>
                                        <div>No. Rekening: <span class="font-mono">{{ $bank['account_number'] }}</span>
                                        </div>
                                        <div>a.n. {{ $bank['account_name'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <form action="{{ route('user.order.store-upload-proof', $order) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            {{-- select option tujuan Transfe --}}
                            <div class="mb-4">
                                <label for="bank_account_id" class="block mb-2 text-sm font-medium text-gray-700">Pilih
                                    Rekening Tujuan
                                    Transfer</label>
                                <select name="bank_account_id" id="bank_account_id" required
                                    class="border rounded px-3 py-2 w-full">
                                    <option value="" disabled selected>Pilih rekening tujuan transfer</option>
                                    @foreach ($bankAccounts as $bank)
                                        <option value="{{ $bank['bank_name'] }}">{{ $bank['bank_name'] }} -
                                            {{ $bank['account_number'] }} a.n. {{ $bank['account_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="block mb-2 text-sm font-medium text-gray-700">Nominal
                                    Transfer</label>
                                <input type="number" name="amount" id="amount" min="1" step="any"
                                    required class="border rounded px-3 py-2 w-full"
                                    placeholder="Masukkan nominal transfer">
                            </div>
                            <div class="mb-4">
                                <label for="sender_name" class="block mb-2 text-sm font-medium text-gray-700">Nama
                                    Pengirim</label>
                                <input type="text" name="sender_name" id="sender_name" required
                                    class="border rounded px-3 py-2 w-full" placeholder="Nama sesuai rekening pengirim">
                            </div>
                            <div class="mb-4">
                                <label for="sender_bank" class="block mb-2 text-sm font-medium text-gray-700">Bank
                                    Pengirim</label>
                                <input type="text" name="sender_bank" id="sender_bank" required
                                    class="border rounded px-3 py-2 w-full" placeholder="Nama bank pengirim">
                            </div>
                            <div class="mb-6">
                                <label for="proof" class="block mb-2 text-sm font-medium text-gray-700">Upload
                                    Bukti
                                    Transfer</label>
                                <input type="file" name="proof" id="proof" accept="image/*,application/pdf"
                                    required class="border rounded px-3 py-2 w-full">
                                <p class="text-xs text-gray-500 mt-1">Format gambar (jpg, png) atau PDF. Maksimal
                                    2MB.
                                </p>
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700 transition">Kirim
                                Bukti Transfer</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</x-app-layout>
