<x-app-layout>
    <x-slot name="title">
        {{ __('Daftar Pesanan Saya') }}
    </x-slot>

    {{-- back to profile --}}

    <div class="p-12 mt-6">
        <div class="container mx-auto px-4 mb-6">
            <ul class="flex space-x-2 text-sm">
                <li><a href="#" class="text-blue-600 hover:underline">Home</a></li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-600">Order</li>
            </ul>
        </div>
        <div class="mt-4">
            <a href="{{ route('profile.show') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Profil
            </a>
        </div>
        <!-- Navigation Tabs -->
        <div class="mt-8 mb-6 px-4">
            @php
                $statusTabs = [
                    'pending' => 'Belum Bayar',
                    'processing' => 'Sedang Di Proses',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ];
            @endphp
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs" id="order-tabs">
                    @foreach ($statusTabs as $key => $label)
                        <a href="#" data-status="{{ $key }}"
                            class="order-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $loop->first ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
        <!-- Orders Card List -->
        <section class="py-4">
            <div class="container mx-auto px-4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Daftar Pesanan</h2>
                    {{-- @php
                        $orders = collect([
                            (object) [
                                'order_number' => 'ORD001',
                                'created_at' => now(),
                                'payment' => (object) ['amount' => 150000],
                                'status' => 'unpaid',
                                'products' => [
                                    (object) [
                                        'name' => 'Sepatu Sneakers Pria',
                                        'price' => 150000,
                                        'quantity' => 2,
                                        'image' => 'https://picsum.photos/seed/sepatu/80/80',
                                    ],
                                    (object) [
                                        'name' => 'Kaos Polos',
                                        'quantity' => 1,

                                        'image' => 'https://picsum.photos/seed/kaos/80/80',
                                    ],
                                ],
                            ],
                            (object) [
                                'order_number' => 'ORD002',
                                'created_at' => now()->subDays(1),
                                'payment' => (object) ['amount' => 250000],
                                'status' => 'processing',
                                'products' => [
                                    (object) [
                                        'name' => 'Tas Ransel',
                                        'price' => 150000,
                                        'quantity' => 1,
                                        'image' => 'https://picsum.photos/seed/tas/80/80',
                                    ],
                                ],
                            ],
                            (object) [
                                'order_number' => 'ORD003',
                                'created_at' => now()->subDays(2),
                                'payment' => (object) ['amount' => 350000],
                                'status' => 'completed',
                                'products' => [
                                    (object) [
                                        'name' => 'Jaket Hoodie',
                                        'price' => 350000,
                                        'quantity' => 1,
                                        'image' => 'https://picsum.photos/seed/jaket/80/80',
                                    ],
                                ],
                            ],
                            (object) [
                                'order_number' => 'ORD004',
                                'created_at' => now()->subDays(3),
                                'payment' => (object) ['amount' => 100000],
                                'status' => 'cancelled',
                                'products' => [
                                    (object) [
                                        'name' => 'Topi Baseball',
                                        'quantity' => 3,
                                        'price' => 100000,
                                        'image' => 'https://picsum.photos/seed/topi/80/80',
                                    ],
                                ],
                            ],
                        ]);
                    @endphp --}}

                    @foreach ($statusTabs as $statusKey => $statusLabel)
                        @php
                            $filteredOrders = $orders->where('status', $statusKey);
                        @endphp
                        <div class="order-table" id="table-{{ $statusKey }}"
                            style="{{ $loop->first ? '' : 'display:none;' }}">
                            @if ($filteredOrders->count())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach ($filteredOrders as $order)
                                        <div class="border rounded-lg shadow p-5 flex flex-col gap-3 bg-gray-50">
                                            <div class="flex justify-between items-center">
                                                <span
                                                    class="font-mono font-semibold text-lg">#{{ $order->order_number }}</span>
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span
                                                            class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs">Belum
                                                            Bayar</span>
                                                    @break

                                                    @case('processing')
                                                        <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs">Sedang
                                                            Di Proses</span>
                                                    @break

                                                    @case('completed')
                                                        <span
                                                            class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs">Selesai</span>
                                                    @break

                                                    @case('cancelled')
                                                        <span
                                                            class="px-2 py-1 rounded bg-red-100 text-red-800 text-xs">Dibatalkan</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs">{{ ucfirst($order->status) }}</span>
                                                @endswitch
                                            </div>
                                            <div class="flex items-center gap-4 mt-2">
                                                <img src="{{ $order->items->first()->product->getFirstMediaUrl('*') }}"
                                                    alt="Product Image" class="w-20 h-20 object-cover rounded">
                                                <div>
                                                    <div class="font-semibold text-base">
                                                        {{ $order->items->first()->product->name }}</div>
                                                    <div class="text-sm text-gray-600">
                                                        {{ $order->items->first()->price ? 'Rp. ' . number_format($order->items->first()->price, 2) : 'Gratis' }}
                                                        x
                                                        {{ $order->items->first()->quantity }}</div>
                                                </div>
                                            </div>
                                            <div class="text-gray-600 text-sm">
                                                <div><span class="font-semibold">Tanggal:</span>
                                                    {{ $order->created_at->format('d M Y H:i') }}</div>
                                                <div><span class="font-semibold">Total:</span> Rp.
                                                    {{ number_format($order->payment->amount ?? 0, 2) }}</div>
                                            </div>
                                            <div class="mt-2 flex gap-2">
                                                <a href="{{ route('user.order.show', $order) }}"
                                                    class="text-blue-600 hover:underline text-sm">Detail</a>
                                                @if ($order->status === 'pending')
                                                    <span class="mx-1 text-gray-400">|</span>
                                                    <a href="{{ route('user.order.get-upload-proof', $order) }}"
                                                        class="text-indigo-600 hover:underline text-sm">Upload
                                                        Bukti</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-12">
                                    Tidak ada pesanan pada status ini.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.order-tab');
            const tables = document.querySelectorAll('.order-table');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Remove active classes
                    tabs.forEach(t => t.classList.remove('border-blue-600', 'text-blue-600'));
                    tabs.forEach(t => t.classList.add('border-transparent', 'text-gray-500',
                        'hover:text-gray-700', 'hover:border-gray-300'));
                    // Add active class to clicked tab
                    tab.classList.remove('border-transparent', 'text-gray-500',
                        'hover:text-gray-700', 'hover:border-gray-300');
                    tab.classList.add('border-blue-600', 'text-blue-600');
                    // Show corresponding table
                    const status = tab.getAttribute('data-status');
                    tables.forEach(table => {
                        if (table.id === 'table-' + status) {
                            table.style.display = '';
                        } else {
                            table.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
