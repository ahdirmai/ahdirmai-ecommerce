<x-admin-layout>
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Order Management</h1>
                <p class="text-muted mb-0">Manage your Orders</p>
            </div>
        </div>

        @include('admin.layouts.alerts')

        <div class="row">
            <div class="col-12 text-end">
                {{-- No add order button --}}
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-ul me-2"></i>
                                Orders
                            </h5>
                            <!-- Search -->
                            <div class="d-flex gap-2">
                                <div class="position-relative">
                                    <form method="GET" action="{{ route('admin.orders.index') }}">
                                        <input type="text" class="form-control form-control-sm pe-4" name="search"
                                            id="searchInput" placeholder="Search orders..." style="min-width: 200px;"
                                            value="{{ request('search') }}">
                                    </form>
                                    <i
                                        class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-2 text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="ps-4" style="width: 60px;">#</th>
                                        <th scope="col">Order Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">Total Paid</th>
                                        <th scope="col">Payment Method</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                        <th scope="col" class="text-center" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (@$orders as $order)
                                        <tr>
                                            <td class="ps-4 text-muted">
                                                # {{-- {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }} --}}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="text-decoration-none text-dark">
                                                    {{ $order->order_number ?? '-' }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $order->user->name ?? '-' }}
                                            </td>
                                            <td>
                                                Rp. {{ number_format($order->total_amount, 2) }}
                                                {{-- {{ $order->currency ?? 'USD' }} --}}
                                            </td>
                                            <td>
                                                Rp. {{ number_format($order->payment->amount_paid, 2) }}
                                                {{-- {{ $order->currency ?? 'USD' }} --}}
                                            </td>
                                            <td>
                                                {{ $order->payment->payment_method ?? '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($order->status) {
                                                        'pending' => 'bg-warning',
                                                        'paid' => 'bg-success',
                                                        'cancelled' => 'bg-danger',
                                                        'processing' => 'bg-info',
                                                        default => 'bg-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.orders.show', $order) }}"
                                                        class="btn btn-outline-secondary" title="View Order">
                                                        <iconify-icon icon="solar:eye-line-duotone"
                                                            class="me-1"></iconify-icon>
                                                    </a>
                                                    {{-- <button type="button" class="btn btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-id="{{ $order->id }}" title="Delete Order"
                                                        data-route="{{ route('admin.orders.destroy', $order->id) }}">
                                                        <iconify-icon
                                                            icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                                    </button> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="text-center py-5">
                                                    <i class="bi bi-folder2-open display-1 text-muted mb-3"></i>
                                                    <h5 class="text-muted">No Orders Found</h5>
                                                    <p class="text-muted mb-0">No orders have been placed yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{-- {{ $orders->withQueryString()->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modal-section')
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            Confirm Deletion
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <p class="mb-3">Are you sure you want to delete the Order <strong id="deleteOrderCode"></strong>?
                        </p>
                        <div class="alert alert-warning border-warning bg-warning bg-opacity-10">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Warning:</strong> This action cannot be undone. Data associated with this order may be
                            affected.
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Cancel
                        </button>
                        <form id="deleteForm" method="POST" style="display: inline;" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>Delete Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('after-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteModal = document.getElementById('deleteModal');
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const orderId = button.getAttribute('data-id');
                    const orderCode = button.closest('tr').querySelector('td:nth-child(2)').textContent.trim();
                    const actionUrl = button.getAttribute('data-route');

                    document.getElementById('deleteOrderCode').textContent = orderCode;
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.setAttribute('action', actionUrl);
                });
            });
        </script>
    @endpush

</x-admin-layout>
