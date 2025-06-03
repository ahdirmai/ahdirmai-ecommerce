<x-admin-layout>
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Order Details</h1>
                <p class="text-muted mb-0">Detail informasi pesanan</p>
            </div>
        </div>

        @include('admin.layouts.alerts')

        <!-- Order Info Card -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-receipt me-2"></i>
                            Informasi Order
                        </h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">Order ID</dt>
                            <dd class="col-sm-8">{{ $order->order_number }}</dd>

                            <dt class="col-sm-4 text-muted">Nama Customer</dt>
                            <dd class="col-sm-8">{{ $order->user->name ?? '-' }}</dd>

                            <dt class="col-sm-4 text-muted">Email Customer</dt>
                            <dd class="col-sm-8">{{ $order->user->email ?? '-' }}</dd>

                            <dt class="col-sm-4 text-muted">Tanggal Order</dt>
                            <dd class="col-sm-8">{{ $order->created_at->format('d M Y H:i') }}</dd>

                            <dt class="col-sm-4 text-muted">Metode Pembayaran</dt>
                            <dd class="col-sm-8">{{ $order->payment->payment_method ?? '-' }}</dd>

                            <dt class="col-sm-4 text-muted">Total Harga</dt>
                            <dd class="col-sm-8">
                                <strong>{{ number_format($order->total_amount, 2) }}
                                    {{ $order->currency ?? 'IDR' }}</strong>
                            </dd>

                            <dt class="col-sm-4 text-muted">Total Dibayar</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-success">
                                    {{ number_format($order->payment->amount_paid, 2) }} {{ $order->currency ?? 'IDR' }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box-seam me-2"></i>
                            Produk yang Dipesan
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product && $item->product->getFirstMedia('product_images'))
                                                        <img src="{{ $item->product->getFirstMediaUrl('product_images', 'thumb') }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="img-fluid rounded me-2"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('assets/images/no-image.png') }}"
                                                            alt="No Image" class="img-fluid rounded me-2"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <span>
                                                        {{ $item->product->name ?? '-' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->price, 2) }} {{ $order->currency ?? 'IDR' }}
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>
                                                {{ number_format($item->price * $item->quantity, 2) }}
                                                {{ $order->currency ?? 'IDR' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($order->items->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Tidak ada produk pada pesanan ini.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total</th>
                                        <th>
                                            {{ number_format($order->total_amount, 2) }}
                                            {{ $order->currency ?? 'IDR' }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- payment History Table --}}
        <div class="row mt-4">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-credit-card me-2"></i>
                            Riwayat Pembayaran
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pengirim</th>
                                        <th>Bank Pengirim</th>
                                        <th>Bank Tujuan</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->payment->paymentHistories as $history)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $history->sender_name ?? '-' }}</td>
                                            <td>{{ $history->bank_name ?? '-' }}</td>
                                            <td>{{ $history->bank_receiver ?? '-' }}</td>
                                            <td>{{ number_format($history->amount, 2) }}
                                                {{ $order->currency ?? 'IDR' }}</td>
                                            <td>
                                                {{-- {{ $history->status }} --}}
                                                {{-- <span class="badge bg-{{ $history->status === 'completed' ? 'success' : ($history->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($history->status) }} --}}
                                                @if ($history->status === 'Completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif ($history->status == 'Pending')
                                                    <span class="badge bg-warning">Tertunda</span>
                                                @else
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- button trigger modal show proof --}}
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    data-proof-image="{{ $history->getFirstMediaUrl('proofs') }}"
                                                    data-bs-toggle="modal" data-bs-target="#proofModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                {{-- accept --}}
                                                @if ($history->status == 'Pending')
                                                    <form
                                                        action="{{ route('admin.orders.update-status', [$order, $history]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Completed">
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            title="Terima">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    </form>

                                                    <form
                                                        action="{{ route('admin.orders.update-status', [$order, $history]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Rejected">
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Tolak">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    </form>

                                                    {{-- edit --}}
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#editAmountModal"
                                                        data-history-id="{{ $history->id }}"
                                                        data-amount="{{ $history->amount }}"
                                                        data-route="{{ route('admin.payment.update-amount', $history) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Tidak ada riwayat pembayaran untuk pesanan ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('modal-section')
            <div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="proofModalLabel">Bukti Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body
                        text-center">
                            <img id="proofImage" src="" alt="Bukti Pembayaran" class="img-fluid">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <a id="downloadProof" href="#" class="btn btn-primary" download>Unduh Bukti</a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="editAmountModal" tabindex="-1" aria-labelledby="editAmountModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAmountModalLabel">Edit Jumlah Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="editAmountForm" method="POST" action="">
                            @csrf
                            <input type="hidden" name="history_id" id="history_id">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Jumlah Pembayaran</label>
                                    <input type="number" class="form-control" id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endpush

        @push('after-scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const proofModal = document.getElementById('proofModal');
                    const proofImage = document.getElementById('proofImage');
                    const downloadProof = document.getElementById('downloadProof');

                    proofModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const imageUrl = button.getAttribute('data-proof-image');

                        proofImage.src = imageUrl;
                        downloadProof.href = imageUrl;
                    });
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const editAmountModal = document.getElementById('editAmountModal');
                    const editAmountForm = document.getElementById('editAmountForm');

                    editAmountModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const historyId = button.getAttribute('data-history-id');
                        const amount = button.getAttribute('data-amount');
                        const route = button.getAttribute('data-route');

                        document.getElementById('history_id').value = historyId;
                        document.getElementById('amount').value = amount;
                        editAmountForm.action = route;
                    });
                });
            </script>
        @endpush
</x-admin-layout>
