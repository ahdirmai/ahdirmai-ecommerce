<x-admin-layout>
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Category Management</h1>
                <p class="text-muted mb-0">Manage your product categories</p>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Category Form -->
            @include('admin.pages.categories.category-form')

            <!-- Categories List -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-ul me-2"></i>
                                Categories
                            </h5>

                            <!-- Search -->
                            <div class="d-flex gap-2">
                                <div class="position-relative">

                                    <form method="GET" action="{{ route('admin.categories.index') }}">
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
                                        <th scope="col">Icon</th>
                                        <th scope="col">Name</th>
                                        {{-- description --}}
                                        <th scope="col">Description</th>
                                        <th scope="col" style="width: 120px;">Type</th>
                                        <th scope="col" style="width: 130px;">Created</th>
                                        <th scope="col" class="text-center" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryTableBody">
                                    @forelse ($categories as $cat)
                                        <tr data-name="{{ strtolower($cat->name) }}"
                                            data-type="{{ strtolower($cat->type) }}">
                                            <td class="ps-4 text-muted">
                                                {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <i class="fas {{ $cat->icon }} text-muted"></i>
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ $cat->name }}</div>
                                                @if ($cat->slug)
                                                    <small class="text-muted">{{ $cat->slug }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div
                                                    class="text-muted
                                                    {{ $cat->description ? '' : 'text-muted' }}">
                                                    {{ $cat->description ?: 'No description provided' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $cat->type === 'digital' ? 'info' : 'success' }} bg-opacity-10 text-{{ $cat->type === 'digital' ? 'info' : 'success' }}">
                                                    <i
                                                        class="bi bi-{{ $cat->type === 'digital' ? 'cloud' : 'box' }} me-1"></i>
                                                    {{ ucfirst($cat->type) }}
                                                </span>
                                            </td>
                                            <td class="text-muted">
                                                <small>{{ $cat->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button data-route="{{ route('admin.categories.update', $cat) }}"
                                                        data-id="{{ $cat->id }}" data-name="{{ $cat->name }}"
                                                        data-icon="{{ $cat->icon }}"
                                                        data-description="{{ $cat->description }}"'
                                                        data-type="{{ $cat->type }}"
                                                        class="btn btn-outline-warning btn-sm"
                                                        title="Edit {{ $cat->name }}" name="editCategoryBtn">
                                                        <iconify-icon icon="solar:pen-2-line-duotone"
                                                            class="me-1"></iconify-icon>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-sm delete-btn"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-category-id="{{ $cat->id }}"
                                                        data-category-name="{{ $cat->name }}"
                                                        data-route="{{ route('admin.categories.destroy', $cat) }}"
                                                        title="Delete {{ $cat->name }}">
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
                            {{ $categories->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modal-section')
        <!-- Delete Confirmation Modal -->
        @include('admin.pages.categories.delete-modal')
    @endpush

    @push('after-scripts')
        <script>
            // Handle Edit Button Click
            document.querySelectorAll('[name="editCategoryBtn"]').forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const name = this.getAttribute('data-name');
                    const type = this.getAttribute('data-type');
                    const id = this.getAttribute('data-id');
                    const icon = this.getAttribute('data-icon');
                    const description = this.getAttribute('data-description');

                    document.getElementById('formTitle').innerHTML =
                        `<i class="bi bi-pencil-square me-2"></i>Edit Category: ${name}`;
                    document.getElementById('name').value = name;
                    document.getElementById('type').value = type;
                    document.getElementById('categoryForm').action = route;
                    document.getElementById('icon').value = icon;
                    document.getElementById('description').value = description;

                    // add method hidden input put
                    const methodInput = document.createElement('input');
                    methodInput.setAttribute('type', 'hidden');
                    methodInput.setAttribute('name', '_method');
                    methodInput.setAttribute('value', 'PUT');
                    // append method input to form
                    document.getElementById('categoryForm').appendChild(methodInput);


                    // change submit button text
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = `<i class="bi bi-pencil-square me-1"></i>Update Category`;
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-warning');

                    // add button cancel
                    const cancelBtn = document.createElement('button');
                    cancelBtn.setAttribute('type', 'button');
                    cancelBtn.classList.add('btn', 'btn-secondary', 'mt-2');
                    cancelBtn.innerHTML = `<i class="bi bi-x-lg me-1"></i>Cancel Edit`;
                    cancelBtn.addEventListener('click', function() {
                        // Reset form
                        document.getElementById('categoryForm').reset();
                        document.getElementById('categoryForm').action =
                            "{{ route('admin.categories.store') }}";
                        document.getElementById('formTitle').innerHTML =
                            `<i class="bi bi-plus-circle me-2"></i>Add New Category`;
                        submitBtn.innerHTML = `<i class="bi bi-plus-lg me-1"></i>Create Category`;
                        submitBtn.classList.remove('btn-warning');
                        submitBtn.classList.add('btn-primary');
                        // Remove method input if exists
                        const methodInput = document.querySelector('input[name="_method"]');
                        if (methodInput) {
                            methodInput.remove();
                        }
                        // Remove cancel button
                        const formActions = document.getElementById('formActions');
                        if (formActions.contains(cancelBtn)) {
                            formActions.removeChild(cancelBtn);
                        }
                    });
                    // Append cancel button
                    const formActions = document.getElementById('formActions');
                    formActions.appendChild(cancelBtn);
                });
            });
        </script>
    @endpush

    @push('after-scripts')
        <script>
            // Handle Delete Button Click
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const categoryName = this.getAttribute('data-category-name');
                    const categoryId = this.getAttribute('data-category-id');
                    const route = this.getAttribute('data-route');


                    // Set category name in modal
                    document.getElementById('deleteCategoryName').textContent = categoryName;

                    // Set form action
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.action = route;
                });
            });
        </script>
    @endpush

</x-admin-layout>
