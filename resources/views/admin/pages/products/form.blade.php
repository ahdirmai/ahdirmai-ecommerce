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
                <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="product-form"
                    class="need-validation">
                    {{-- basic Information --}}
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
                                    <label for="name" class="form-label">
                                        Product Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', @$product->name ?? '') }}"
                                        required minlength="2" maxlength="255">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Sort Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="4" maxlength="1000">{{ old('description', @$product->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum 1000 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label for="long_description" class="form-label"> Long Description</label>
                                    <textarea class="form-control @error('long_description') is-invalid @enderror" id="long_description"
                                        name="long_description" rows="4" maxlength="1000">{{ old('long_description', @$product->long_description ?? '') }}</textarea>
                                    @error('long_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum 5000 characters</small>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="product_type" class="form-label">
                                            Product Type <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('product_type') is-invalid @enderror"
                                            id="product_type" name="product_type" required>
                                            <option value="">-- Select Product Type --</option>
                                            <option value="physical"
                                                {{ old('product_type', @$product->product_type ?? 'physical') == 'physical' ? 'selected' : '' }}>
                                                Physical
                                            </option>
                                            <option value="digital"
                                                {{ old('product_type', @$product->product_type ?? '') == 'digital' ? 'selected' : '' }}>
                                                Digital
                                            </option>
                                        </select>
                                        @error('product_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    data-product-type="{{ $category->product_type }}"
                                                    {{ old('category_id', @$product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- product Digital Basic --}}
                                <div class="row mb-3" id="digital-fields"
                                    style="{{ old('product_type', @$product->product_type ?? 'physical') == 'digital' ? '' : 'display: none;' }}">
                                    <div class="col-md-6">
                                        <label for="format" class="form-label">
                                            Format <span class="text-danger digital-required">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('format') is-invalid @enderror"
                                            id="format" name="format"
                                            value="{{ old('format', @$product->format ?? '') }}"
                                            placeholder="e.g. PDF, EPUB, MP3">
                                        @error('format')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="file_size" class="form-label">
                                            File Size <span class="text-danger digital-required">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('file_size') is-invalid @enderror" id="file_size"
                                            name="file_size" value="{{ old('file_size', @$product->file_size ?? '') }}"
                                            placeholder="e.g. 10MB, 500KB">
                                        @error('file_size')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                                    // Clear validation errors when hiding
                                                    formatInput.classList.remove('is-invalid');
                                                    fileSizeInput.classList.remove('is-invalid');
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

                                            const oldCategoryValue = "{{ old('category_id', @$product->category_id ?? '') }}";
                                            const currentProductType = "{{ old('product_type', @$product->product_type ?? 'physical') }}";

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

                    {{-- product Pricing --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="p-4">
                                <div class="mb-3 text-center">
                                    Product Pricing Information
                                </div>

                                <div class="mb-3">
                                    <label for="pricing_type" class="form-label">
                                        Pricing Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('pricing_type') is-invalid @enderror"
                                        id="pricing_type" name="pricing_type" required>
                                        <option value="">-- Select Pricing Type --</option>
                                        <option value="basic"
                                            {{ old('pricing_type', @$product->pricing_type ?? 'basic') == 'basic' ? 'selected' : '' }}>
                                            Basic</option>
                                        {{-- <option value="variant"
                                            {{ old('pricing_type', @$product->pricing_type ?? '') == 'variant' ? 'selected' : '' }}
                                            disabled>
                                            Using Variant</option> --}}
                                    </select>
                                    @error('pricing_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="basic-pricing-section" style="display: none;">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">
                                                Price <span class="text-danger basic-required">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('price') is-invalid @enderror"
                                                    id="price" name="price"
                                                    value="{{ old('price', @$product->price ?? '') }}" min="0"
                                                    max="999999.99">
                                            </div>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Maximum: $999,999.99</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="stock" class="form-label">Stock</label>
                                            <input type="number"
                                                class="form-control @error('stock') is-invalid @enderror"
                                                id="stock" name="stock"
                                                value="{{ old('stock', @$product->stock ?? 0) }}" min="0"
                                                max="999999">
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Leave blank or 0 for unlimited
                                                stock</small>
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
                                                    <th>Variant Name <span class="text-danger">*</span></th>
                                                    <th>Price <span class="text-danger">*</span></th>
                                                    <th>Stock <span class="text-danger">*</span></th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $variants = old('variants', @$product->variants ?? []);
                                                @endphp
                                                @if (!empty($variants))
                                                    @foreach ($variants as $i => $variant)
                                                        <tr>
                                                            <td>
                                                                <input type="text"
                                                                    name="variants[{{ $i }}][name]"
                                                                    class="form-control @error('variants.' . $i . '.name') is-invalid @enderror"
                                                                    value="{{ $variant['name'] ?? '' }}" required>
                                                                @error('variants.' . $i . '.name')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01"
                                                                    name="variants[{{ $i }}][price]"
                                                                    class="form-control @error('variants.' . $i . '.price') is-invalid @enderror"
                                                                    value="{{ $variant['price'] ?? '' }}"
                                                                    min="0" max="999999.99" required>
                                                                @error('variants.' . $i . '.price')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    name="variants[{{ $i }}][stock]"
                                                                    class="form-control @error('variants.' . $i . '.stock') is-invalid @enderror"
                                                                    value="{{ $variant['stock'] ?? 0 }}"
                                                                    min="0" max="999999" required>
                                                                @error('variants.' . $i . '.stock')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
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
                                                } else if (pricingTypeSelect.value === 'basic') {
                                                    variantSection.style.display = 'none';
                                                    basicSection.style.display = '';
                                                    priceInput.required = true;
                                                    stockInput.required = false;
                                                } else {
                                                    variantSection.style.display = 'none';
                                                    basicSection.style.display = 'none';
                                                    priceInput.required = false;
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
                                                    <input type="number" step="0.01" name="variants[${rowCount}][price]" class="form-control" min="0" max="999999.99" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="variants[${rowCount}][stock]" class="form-control" min="0" max="999999" required>
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


                    {{-- Key Feature (detail) --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="p-4">
                                <div class="mb-3 text-center">
                                    Product Key Features
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="key_features_toggle"
                                        name="has_key_features"
                                        {{ old('has_key_features', @$product->details?->count() > 0 ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="key_features_toggle">Enable Key
                                        Features</label>
                                </div>

                                {{-- Dynamic Key Features --}}
                                <div id="key_features_section"
                                    style="{{ old('has_key_features', @$product->details?->count() > 0 ?? false) ? '' : 'display: none;' }}">
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
                                                    <th>Name <span class="text-danger">*</span></th>
                                                    <th>Description <span class="text-danger">*</span></th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $keyfeatures = old('keyfeatures', @$product->details ?? []);
                                                @endphp
                                                @if (!empty($keyfeatures))
                                                    @foreach ($keyfeatures as $i => $kf)
                                                        <tr>
                                                            {{-- hidden id --}}
                                                            <input type="hidden"
                                                                name="keyfeatures[{{ $i }}][id]"
                                                                value="{{ $kf['id'] ?? '' }}">
                                                            {{-- key feature input --}}
                                                            <td>
                                                                <input type="text"
                                                                    name="keyfeatures[{{ $i }}][icon]"
                                                                    class="form-control @error('keyfeatures.' . $i . '.icon') is-invalid @enderror"
                                                                    value="{{ $kf['attribute_icon'] ?? '' }}"
                                                                    placeholder="e.g. fa-check">
                                                                @error('keyfeatures.' . $i . '.icon')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="keyfeatures[{{ $i }}][name]"
                                                                    class="form-control keyfeature-name @error('keyfeatures.' . $i . '.name') is-invalid @enderror"
                                                                    value="{{ $kf['attribute_name'] ?? '' }}"
                                                                    required>
                                                                @error('keyfeatures.' . $i . '.name')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <textarea name="keyfeatures[{{ $i }}][description]"
                                                                    class="form-control keyfeature-description @error('keyfeatures.' . $i . '.description') is-invalid @enderror"
                                                                    rows="2" required>{{ $kf['attribute_value'] ?? '' }}</textarea>
                                                                @error('keyfeatures.' . $i . '.description')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
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

                                                // Set required attribute based on toggle state
                                                const nameInputs = section.querySelectorAll('.keyfeature-name');
                                                const descInputs = section.querySelectorAll('.keyfeature-description');

                                                nameInputs.forEach(input => {
                                                    input.required = toggle.checked;
                                                });
                                                descInputs.forEach(input => {
                                                    input.required = toggle.checked;
                                                });
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
                                                    <input type="text" name="keyfeatures[${rowCount}][name]" class="form-control keyfeature-name" required>
                                                </td>
                                                <td>
                                                    <textarea name="keyfeatures[${rowCount}][description]" class="form-control keyfeature-description" rows="2" required></textarea>
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
                                    <input type="file"
                                        class="form-control @error('gallery') is-invalid @enderror @error('gallery.*') is-invalid @enderror"
                                        id="gallery" name="gallery[]" accept=".jpg,.jpeg,.png,.gif,.svg" multiple>
                                    @error('gallery')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('gallery.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Accepted formats: JPG, JPEG, PNG, GIF, SVG. Maximum size: 2MB per image.
                                    </small>
                                    {{-- Preview selected images --}}
                                    <div id="gallery-preview" class="mt-2"></div>
                                    {{-- Display existing images if editing --}}
                                    @if (isset($product) && @$product->media->count() > 0)
                                        <div class="mt-3">
                                            <h5>Existing Images:</h5>
                                            <div class="d-flex flex-wrap" id="existing-gallery">
                                                @foreach (@$product->media as $media)
                                                    <div class="position-relative me-2 mb-2"
                                                        style="display:inline-block;">
                                                        <img src="{{ $media->getUrl() }}" class="img-thumbnail"
                                                            style="max-width: 100px;">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-existing-image"
                                                            style="z-index:2; padding:0.1rem 0.4rem; font-size:0.8rem;"
                                                            data-media-id="{{ $media->id }}">&times;</button>
                                                        <input type="hidden" name="existing_gallery[]"
                                                            value="{{ $media->id }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
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

                                // Remove existing image preview
                                const existingGallery = document.getElementById('existing-gallery');
                                if (existingGallery) {
                                    existingGallery.addEventListener('click', function(e) {
                                        if (e.target.classList.contains('remove-existing-image')) {
                                            const wrapper = e.target.closest('.position-relative');
                                            if (wrapper) {
                                                // Remove the hidden input so it won't be submitted
                                                const hiddenInput = wrapper.querySelector(
                                                    'input[type="hidden"][name="existing_gallery[]"]');
                                                if (hiddenInput) {
                                                    hiddenInput.remove();
                                                }
                                                wrapper.remove();
                                            }
                                        }
                                    });
                                }
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
