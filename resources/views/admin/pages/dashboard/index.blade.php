<x-admin-layout>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="fw-semibold fs-4 mb-2">Total Product</h6>
                    <h3 class="fw-bold mb-0">{{ $totalProducts ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="fw-semibold fs-4 mb-2">Total Order</h6>
                    <h3 class="fw-bold mb-0">{{ $totalOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="fw-semibold fs-4 mb-2">Total Revenue</h6>
                    <h3 class="fw-bold mb-0">Rp. {{ number_format($totalRevenue ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
