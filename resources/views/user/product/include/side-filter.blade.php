<form method="GET" action="{{ route('products.index') }}">
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="font-bold text-lg mb-4">Filters</h3>

        <!-- Product Type -->
        <div class="mb-6">
            <h4 class="font-medium mb-3 text-gray-800">Product Type</h4>
            <select name="product_type" id="product_type_select" class="form-select w-full rounded-lg border-gray-300">
                <option value="">All Types</option>
                <option value="digital" {{ request('product_type') == 'digital' ? 'selected' : '' }}>Digital</option>
                <option value="physical" {{ request('product_type') == 'physical' ? 'selected' : '' }}>Physical</option>
            </select>
        </div>

        <!-- Categories -->
        <div class="mb-6">
            <h4 class="font-medium mb-3 text-gray-800">Categories</h4>
            <div class="space-y-2" id="categories_container">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium">
            Apply Filters
        </button>
    </div>

    @push('after-scripts')
        <script>
            const categoryOptions = @json($categoryOptions);
            const selectedCategories = @json(request('categories', []));
            const categoriesContainer = document.getElementById('categories_container');
            const productTypeSelect = document.getElementById('product_type_select');

            function renderCategories(selectedType = '') {
                categoriesContainer.innerHTML = '';

                categoryOptions.forEach(option => {
                    if (selectedType === '' || option.product_type === selectedType) {
                        const label = document.createElement('label');
                        label.className = 'flex items-center';
                        label.setAttribute('data-product-type', option.product_type);

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.className = 'form-checkbox h-4 w-4 text-blue-600';
                        checkbox.name = 'categories[]';
                        checkbox.value = option.slug;

                        // Set checked if in selectedCategories
                        if (selectedCategories.includes(option.slug)) {
                            checkbox.checked = true;
                        }

                        const span = document.createElement('span');
                        span.className = 'ml-2 text-gray-700';
                        span.textContent = option.text;

                        label.appendChild(checkbox);
                        label.appendChild(span);
                        categoriesContainer.appendChild(label);
                    }
                });
            }

            productTypeSelect.addEventListener('change', function() {
                renderCategories(this.value);
            });

            renderCategories(productTypeSelect.value);
        </script>
    @endpush
</form>
