<x-admin-layout>
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Product Management | {{ $method == 'PUT' ? 'Update Data' : 'Create Data' }}</h1>
                <p class="text-muted mb-0">Manage your Product Data</p>
            </div>
        </div>

        @include('admin.layouts.alerts')


        <div class="row">
            <!-- Categories List -->
            {{-- button add product (a href to route('admin.products.create')) --}}
            <div class="text-start">

                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-3 btn-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Back to Products
                </a>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
                <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-0">
                            <div class="p-4">
                                @csrf
                                @if ($method == 'PUT')
                                    @method('PUT')
                                @endif
                                <div class="mb-3 text-center">
                                    Product Basic Information
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $product->name ?? '') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="product_type" class="form-label">Product Type</label>
                                        <select class="form-select" id="product_type" name="product_type" required>
                                            <option value="physical"
                                                {{ old('product_type', $product->product_type ?? 'physical') == 'physical' ? 'selected' : '' }}>
                                                Physical
                                            </option>
                                            <option value="digital"
                                                {{ old('product_type', $product->product_type ?? '') == 'digital' ? 'selected' : '' }}>
                                                Digital
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select" id="category_id" name="category_id">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    data-product-type="{{ $category->product_type }}"
                                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- product Digital Basic --}}
                                {{-- format (freetext) --}}
                                <div class="row mb-3" id="digital-fields"
                                    style="{{ old('product_type', $product->product_type ?? 'physical') == 'digital' ? '' : 'display: none;' }}">
                                    <div class="col-md-6">
                                        <label for="digital_type" class="form-label">Format</label>
                                        {{-- freetext --}}
                                        <input type="text" class="form-control" id="format" name="format"
                                            value="{{ old('format', $product->format ?? '') }}"
                                            placeholder="e.g. PDF, EPUB, MP3">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="digital_size" class="form-label">File Size</label>
                                        <input type="text" class="form-control" id="file_size" name="file_size"
                                            value="{{ old('file_size', $product->file_size ?? '') }}"
                                            placeholder="e.g. 10MB, 500KB">
                                    </div>
                                </div>

                                @push('after-scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const productTypeSelect = document.getElementById('product_type');
                                            const digitalFields = document.getElementById('digital-fields');
                                            const formatInput = document.getElementById('format');
                                            const fileSizeInput = document.getElementById('file_size');

                                            function toggleDigitalFields() {
                                                if (productTypeSelect.value === 'digital') {
                                                    digitalFields.style.display = '';
                                                    formatInput.required = true;
                                                    fileSizeInput.required = true;
                                                } else {
                                                    digitalFields.style.display = 'none';
                                                    formatInput.required = false;
                                                    fileSizeInput.required = false;
                                                }
                                            }

                                            productTypeSelect.addEventListener('change', toggleDigitalFields);
                                            toggleDigitalFields();
                                        });
                                    </script>
                                @endpush

                                @push('after-scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const productTypeSelect = document.getElementById('product_type');
                                            const categorySelect = document.getElementById('category_id');

                                            // Check if categoryOptions exists, fallback to categories if not
                                            const allOptions = @json($categoryOptions);

                                            const oldCategoryValue = "{{ old('category_id', $product->category_id ?? '') }}";
                                            const currentProductType = "{{ old('product_type', $product->product_type ?? 'physical') }}";

                                            function filterCategories() {
                                                const selectedType = productTypeSelect.value;

                                                // Store currently selected value before clearing
                                                const currentSelection = categorySelect.value;

                                                // Clear all options except the first one (default)
                                                categorySelect.innerHTML = '';

                                                // Add default option
                                                const defaultOption = document.createElement('option');
                                                defaultOption.value = '';
                                                defaultOption.textContent = '-- Select Category --';
                                                categorySelect.appendChild(defaultOption);

                                                // Add filtered options
                                                let hasValidSelection = false;
                                                allOptions.forEach(option => {
                                                    if (option.product_type === selectedType) {
                                                        const opt = document.createElement('option');
                                                        opt.value = option.value;
                                                        opt.textContent = option.text;
                                                        opt.setAttribute('data-product-type', option.product_type);

                                                        // Restore selection if it's still valid for the current product type
                                                        if (option.value == oldCategoryValue || option.value == currentSelection) {
                                                            opt.selected = true;
                                                            hasValidSelection = true;
                                                        }

                                                        categorySelect.appendChild(opt);
                                                    }
                                                });

                                                // If no valid selection found, reset to default
                                                if (!hasValidSelection) {
                                                    categorySelect.value = '';
                                                }

                                                // Trigger change event for any dependent fields
                                                categorySelect.dispatchEvent(new Event('change'));
                                            }

                                            // Event listener for product type changes
                                            productTypeSelect.addEventListener('change', function() {
                                                filterCategories();
                                            });

                                            // Initial load - filter categories based on current product type
                                            filterCategories();

                                            // Additional validation on form submit
                                            const form = categorySelect.closest('form');
                                            if (form) {
                                                form.addEventListener('submit', function(e) {
                                                    const selectedProductType = productTypeSelect.value;
                                                    const selectedCategory = categorySelect.value;

                                                    if (selectedCategory) {
                                                        const selectedOption = categorySelect.querySelector(
                                                            `option[value="${selectedCategory}"]`);
                                                        const categoryProductType = selectedOption ? selectedOption.getAttribute(
                                                            'data-product-type') : null;

                                                        if (categoryProductType && categoryProductType !== selectedProductType) {
                                                            e.preventDefault();
                                                            alert(
                                                                'Selected category does not match the product type. Please select a valid category.'
                                                            );
                                                            return false;
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                @endpush

                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="p-4">
                                <div class="mb-3 text-center">
                                    Product Pricing Information
                                </div>

                                <div class="mb-3">
                                    <label for="pricing_type" class="form-label">Pricing Type</label>
                                    <select class="form-select" id="pricing_type" name="pricing_type" required>
                                        <option value="basic"
                                            {{ old('pricing_type', $product->pricing_type ?? 'basic') == 'basic' ? 'selected' : '' }}>
                                            Basic</option>
                                        {{-- <option value="variant"
                                            {{ old('pricing_type', $product->pricing_type ?? '') == 'variant' ? 'selected' : '' }}
                                            disabled>
                                            Using Variant</option> --}}
                                    </select>
                                </div>

                                <div id="basic-pricing-section" style="display: none;">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" step="0.01" class="form-control" id="price"
                                                name="price" value="{{ old('price', $product->price ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="stock" class="form-label">Stock</label>
                                            <input type="number" class="form-control" id="stock" name="stock"
                                                value="{{ old('stock', $product->stock ?? 0) }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <div id="variant-pricing-section" style="display: none;">
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            id="add-variant-row">
                                            <i class="fa fa-plus"></i> Add Variant
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0" id="variant-table">
                                            <thead>
                                                <tr>
                                                    <th>Variant Name</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $variants = old('variants', $product->variants ?? []);
                                                @endphp
                                                @if (!empty($variants))
                                                    @foreach ($variants as $i => $variant)
                                                        <tr>
                                                            <td>
                                                                <input type="text"
                                                                    name="variants[{{ $i }}][name]"
                                                                    class="form-control"
                                                                    value="{{ $variant['name'] ?? '' }}" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01"
                                                                    name="variants[{{ $i }}][price]"
                                                                    class="form-control"
                                                                    value="{{ $variant['price'] ?? '' }}" required>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    name="variants[{{ $i }}][stock]"
                                                                    class="form-control"
                                                                    value="{{ $variant['stock'] ?? 0 }}"
                                                                    min="0" required>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-variant-row"><i
                                                                        class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @push('after-scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const pricingTypeSelect = document.getElementById('pricing_type');
                                            const basicSection = document.getElementById('basic-pricing-section');
                                            const variantSection = document.getElementById('variant-pricing-section');
                                            const priceInput = document.getElementById('price');
                                            const stockInput = document.getElementById('stock');
                                            const variantTable = document.getElementById('variant-table').getElementsByTagName('tbody')[0];
                                            const addVariantBtn = document.getElementById('add-variant-row');

                                            function togglePricingSections() {
                                                if (pricingTypeSelect.value === 'variant') {
                                                    variantSection.style.display = '';
                                                    basicSection.style.display = 'none';
                                                    priceInput.required = false;
                                                    stockInput.required = false;
                                                } else {
                                                    variantSection.style.display = 'none';
                                                    basicSection.style.display = '';
                                                    priceInput.required = true;
                                                    stockInput.required = false;
                                                }
                                            }

                                            pricingTypeSelect.addEventListener('change', togglePricingSections);
                                            togglePricingSections();

                                            // Add variant row
                                            addVariantBtn.addEventListener('click', function() {
                                                const rowCount = variantTable.rows.length;
                                                const row = variantTable.insertRow();
                                                row.innerHTML = `
                                                <td>
                                                    <input type="text" name="variants[${rowCount}][name]" class="form-control" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="variants[${rowCount}][price]" class="form-control" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="variants[${rowCount}][stock]" class="form-control" min="0" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-variant-row"><i class="fa fa-trash"></i></button>
                                                </td>
                                            `;
                                            });

                                            // Remove variant row
                                            variantTable.addEventListener('click', function(e) {
                                                if (e.target.closest('.remove-variant-row')) {
                                                    const row = e.target.closest('tr');
                                                    row.parentNode.removeChild(row);
                                                }
                                            });
                                        });
                                    </script>
                                @endpush
                            </div>
                        </div>
                    </div>


                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="p-4">
                                <div class="mb-3 text-center">
                                    Product Key Features
                                    {{-- toogle on of --}}

                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="key_features_toggle"
                                        name="has_key_features"
                                        {{ old('has_key_features', $product->has_key_features ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="key_features_toggle">Enable Key
                                        Features</label>
                                </div>

                                {{-- Dynamic Key Features --}}
                                <div id="key_features_section"
                                    style="{{ old('has_key_features', $product->has_key_features ?? false) ? '' : 'display: none;' }}">
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            id="add-keyfeature-row">
                                            <i class="fa fa-plus"></i> Add Key Feature
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0" id="keyfeature-table">
                                            <thead>
                                                <tr>
                                                    <th>Icon</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $keyfeatures = old('keyfeatures', $product->keyfeatures ?? []);
                                                @endphp
                                                @if (!empty($keyfeatures))
                                                    @foreach ($keyfeatures as $i => $kf)
                                                        <tr>
                                                            <td>
                                                                <input type="text"
                                                                    name="keyfeatures[{{ $i }}][icon]"
                                                                    class="form-control"
                                                                    value="{{ $kf['icon'] ?? '' }}"
                                                                    placeholder="e.g. fa-check">
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="keyfeatures[{{ $i }}][name]"
                                                                    class="form-control"
                                                                    value="{{ $kf['name'] ?? '' }}">
                                                            </td>
                                                            <td>
                                                                <textarea name="keyfeatures[{{ $i }}][description]" class="form-control" rows="2">{{ $kf['description'] ?? '' }}</textarea>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-keyfeature-row">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @push('after-scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const toggle = document.getElementById('key_features_toggle');
                                            const section = document.getElementById('key_features_section');
                                            const table = document.getElementById('keyfeature-table').getElementsByTagName('tbody')[0];
                                            const addBtn = document.getElementById('add-keyfeature-row');

                                            function showHideSection() {
                                                section.style.display = toggle.checked ? '' : 'none';
                                            }
                                            toggle.addEventListener('change', showHideSection);
                                            showHideSection();

                                            addBtn.addEventListener('click', function() {
                                                const rowCount = table.rows.length;
                                                const row = table.insertRow();
                                                row.innerHTML = `
                                                <td>
                                                    <input type="text" name="keyfeatures[${rowCount}][icon]" class="form-control" placeholder="e.g. fa-solid fa-check">
                                                </td>
                                                <td>
                                                    <input type="text" name="keyfeatures[${rowCount}][name]" class="form-control">
                                                </td>
                                                <td>
                                                    <textarea name="keyfeatures[${rowCount}][description]" class="form-control" rows="2"></textarea>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-keyfeature-row">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            `;
                                            });

                                            table.addEventListener('click', function(e) {
                                                if (e.target.closest('.remove-keyfeature-row')) {
                                                    const row = e.target.closest('tr');
                                                    row.parentNode.removeChild(row);
                                                }
                                            });
                                        });
                                    </script>
                                @endpush
                            </div>
                        </div>
                    </div>

                    {{-- product gallery --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="p-4">
                                <div class="mb-3 text-center">
                                    Product Gallery
                                </div>
                                <div class="mb-3">
                                    <label for="gallery" class="form-label">Product Gallery Images</label>
                                    <input type="file" class="form-control" id="gallery" name="gallery[]"
                                        accept=".jpg, .jpeg, .png" multiple>
                                    {{-- Preview selected images --}}
                                    <div id="gallery-preview" class="mt-2"></div>
                                    {{-- @if (isset($product) && $product->gallery)
                                        <div class="mt-2">
                                            @foreach ($product->gallery as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image"
                                                    class="img-thumbnail me-2 mb-2" style="max-width: 100px;">
                                            @endforeach
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    @push('after-scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const galleryInput = document.getElementById('gallery');
                                const previewContainer = document.getElementById('gallery-preview');

                                galleryInput.addEventListener('change', function() {
                                    previewContainer.innerHTML = '';
                                    if (galleryInput.files) {
                                        Array.from(galleryInput.files).forEach(file => {
                                            if (file.type.startsWith('image/')) {
                                                const reader = new FileReader();
                                                reader.onload = function(e) {
                                                    const img = document.createElement('img');
                                                    img.src = e.target.result;
                                                    img.className = 'img-thumbnail me-2 mb-2';
                                                    img.style.maxWidth = '100px';
                                                    previewContainer.appendChild(img);
                                                };
                                                reader.readAsDataURL(file);
                                            }
                                        });
                                    }
                                });
                            });
                        </script>
                    @endpush

                    {{-- submit button --}}
                    <div class="text-end">
                        <button type="submit"
                            class="btn btn-primary">{{ $method == 'PUT' ? 'Update Product' : 'Create Product' }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
