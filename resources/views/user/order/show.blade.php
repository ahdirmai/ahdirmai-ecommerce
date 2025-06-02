<x-app-layout>
    <x-slot name="title">
        {{ __('Detail Order') }}
    </x-slot>
    {{-- <section class="min-h-screen w-full flex items-center justify-center pt-12 px-4"> --}}

    {{-- <div class="max-w-2xl mx-auto w-full"> --}}
    <div class="p-12 mt-6 pb-2">
        <div class="container mx-auto px-4 mb-6">
            <ul class="flex space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                <li class="text-gray-500">/</li>
                <li><a href="{{ route('user.order.index') }}" class="text-blue-600 hover:underline">Order</a>
                </li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-600">Detail Order</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('user.order.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Order Page
                </a>
            </div>
        </div>
    </div>
    <!-- Order Detail Content -->
    {{-- <section class="py-8"> --}}
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white p-8 pt-0 rounded-lg shadow space-y-10">
            <div class="bg-white rounded-lg shadow-md p-8 pt-2">
                <h2 class="text-2xl font-bold mb-6">Detail Order</h2>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Informasi Order</h3>
                    <div class="bg-gray-50 rounded p-4">
                        <div><span class="font-semibold">No. Order:</span> {{ $order->order_number ?? '-' }}
                        </div>
                        <div><span class="font-semibold">Tanggal Order:</span>
                            {{ $order->created_at->format('d M Y H:i') }}</div>
                        <div><span class="font-semibold">Status:</span> {{ ucfirst($order->status) }}</div>
                        <div><span class="font-semibold">Total:</span> Rp.
                            {{ number_format($order->payment->amount ?? 0, 2) }}</div>
                        <div><span class="font-semibold">Sudah Dibayar:</span> Rp.
                            {{ number_format($order->payment->amount_paid ?? 0, 2) }}</div>
                        <div><span class="font-semibold">Sisa Pembayaran:</span> Rp.
                            {{ number_format(($order->payment->amount ?? 0) - ($order->payment->amount_paid ?? 0), 2) }}
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Daftar Produk</h3>
                    <div class="bg-gray-50 rounded p-4">
                        <ul class="divide-y">
                            @foreach ($order->items as $item)
                                <li class="py-2 flex justify-between">
                                    <img src="{{ $item->product->getFirstMediaUrl('*') }}"
                                        alt="{{ $item->product->name }}" class="w-16 h-16 object-cover mr-4">
                                    <span>{{ $item->product->name }}</span>
                                    <span>x{{ $item->quantity }}</span>
                                    <span>Rp. {{ number_format($item->price, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('user.order.get-upload-proof', $order) }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded font-semibold hover:bg-blue-700 transition">
                        Upload Bukti Transfer
                    </a>
                </div>
            </div>
        </div>
        {{-- </section> --}}
        <div class="max-w-5xl mx-auto w-full mt-4 mb-4">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h3 class="text-xl font-semibold mb-4">Riwayat Pembayaran</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bank Penerima
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bank Pengirim
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pengirim
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            {{-- action --}}
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($order->payment->paymentHistories as $payment)
                            <tr>
                                <td class="px-4 py-2">{{ $payment->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-2">Rp. {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-2">{{ $payment->bank_name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $payment->sender_bank ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $payment->sender_name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ ucfirst($payment->status ?? '-') }}</td>
                                {{-- button for show image transfer (modal) --}}
                                <td class="px-4 py-2">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                        onclick="showPaymentProofModal('{{ $payment->getFirstMediaUrl('proofs') }}')">
                                        <i class="fas fa-eye mr-1"></i> Lihat Bukti Transfer
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center text-gray-500">Belum ada riwayat
                                    pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- </div> --}}

        {{-- card Payment History --}}
        @push('modal-section')
            <div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="paymentHistoryModal">
                <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-md">
                    <h3 class="text-lg font-semibold mb-4">Bukti Transfer</h3>
                    <img id="paymentImage" src="" alt="Bukti Transfer" class="w-full h-auto rounded-lg mb-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                        onclick="closePaymentHistoryModal()">Tutup</button>
                </div>
            </div>
        @endpush

        @push('after-scripts')
            <script>
                function showPaymentProofModal(imageUrl) {
                    // console.log('showPaymentProofModal called with imageUrl:', imageUrl);
                    const modal = document.getElementById('paymentHistoryModal');
                    const paymentImage = document.getElementById('paymentImage');
                    paymentImage.src = imageUrl;
                    modal.classList.remove('hidden');
                }

                function closePaymentHistoryModal() {
                    const modal = document.getElementById('paymentHistoryModal');
                    modal.classList.add('hidden');
                }
            </script>
        @endpush

        {{-- </section> --}}
</x-app-layout>
