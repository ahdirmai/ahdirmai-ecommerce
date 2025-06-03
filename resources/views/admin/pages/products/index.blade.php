<x-admin-layout>
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Product Management</h1>
                <p class="text-muted mb-0">Manage your Product Datas</p>
            </div>
        </div>

        @include('admin.layouts.alerts')

        <div class="row">
            <!-- Categories List -->
            {{-- button add product (a href to route('admin.products.create')) --}}
            <div class="text-end">

                <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
                    <i class="bi bi-plus-lg me-2"></i>
                    Add Product
                </a>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-ul me-2"></i>
                                Product
                            </h5>

                            <!-- Search -->
                            <div class="d-flex gap-2">
                                <div class="position-relative">

                                    <form method="GET" action="{{ route('admin.products.index') }}">
                                        <input type="text" class="form-control form-control-sm pe-4" name="search"
                                            id="searchInput" placeholder="Search categories..."
                                            style="min-width: 200px;" value="{{ request('search') }}">
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
                                <thead class="table ">
                                    <tr>
                                        <th scope="col" class="ps-4" style="width: 60px;">#</th>
                                        {{-- icon --}}
                                        <th scope="col">Thumbnail</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Description</th>
                                        {{-- <th scope="col" style="width: 120px;">Type</th> --}}
                                        <th scope="col" class="text-muted" style="width: 130px;">Category</th>
                                        <th scope="col" style="width: 130px;">Stock</th>
                                        <th scope="col" class="text-muted" style="width: 130px;">Price</th>
                                        <th scope="col" class="text-center" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryTableBody">
                                    @forelse (@$products as $product)
                                        <tr data-name="{{ strtolower($product->name) }}">
                                            <td class="ps-4 text-muted">
                                                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                {{-- get first product images using spatie media library --}}

                                                @if ($product->getFirstMedia('product_images'))
                                                    <img src="{{ $product->getFirstMediaUrl('product_images', 'thumb') }}"
                                                        alt="{{ $product->name }}" class="img-fluid rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('assets/images/no-image.png') }}" alt="No Image"
                                                        class="img-fluid rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="text-decoration-none text-dark">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted" style="max-width: 200px;">
                                                    {{ Str::limit($product->description, 50) }}
                                                </p>
                                            </td>
                                            <td>
                                                @if ($product->category)
                                                    <span class="badge bg-secondary">
                                                        {{ $product->category->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Uncategorized</span>
                                                @endif

                                            </td>
                                            <td class="text-muted">
                                                {{ $product->stock }}

                                            </td>

                                            <td class="text-muted">
                                                @if ($product->price)
                                                    {{ number_format($product->price, 2) }} {{ $product->currency }}
                                                @else
                                                    <span class="text-danger">Not Set</span>
                                                @endif

                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">

                                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                                        class="btn btn-outline-secondary" title="Edit Product">
                                                        <iconify-icon icon="solar:pen-2-line-duotone"
                                                            class="me-1"></iconify-icon>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-id="{{ $product->id }}" title="Delete Product"
                                                        data-route="{{ route('admin.products.destroy', $product->id) }}">
                                                        <iconify-icon
                                                            icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="text-center py-5">
                                                    <i class="bi bi-folder2-open display-1 text-muted mb-3"></i>
                                                    <h5 class="text-muted">No Categories Found</h5>
                                                    <p class="text-muted mb-0">Create your first category using the
                                                        form on the left.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{-- {{ $products->withQueryString()->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modal-section')
        <!-- Delete Confirmation Modal -->
        {{-- @include('admin.pages.products.delete-modal') --}}
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
                        <p class="mb-3">Are you sure you want to delete the Product <strong
                                id="deleteProductName"></strong>?
                        </p>
                        <div class="alert alert-warning border-warning bg-warning bg-opacity-10">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Warning:</strong> This action cannot be undone. Data associated with this Product
                            may be affected.
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
                                <i class="bi bi-trash me-1"></i>Delete Product
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
                    const productId = button.getAttribute('data-id');
                    const productName = button.closest('tr').querySelector('td:nth-child(3)').textContent
                        .trim();
                    const actionUrl = button.getAttribute('data-route');

                    // Update modal content
                    document.getElementById('deleteProductName').textContent = productName;
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.setAttribute('action', actionUrl);
                });
            });
        </script>
    @endpush

</x-admin-layout>
