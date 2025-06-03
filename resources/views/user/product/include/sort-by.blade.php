<span class="text-gray-600 mr-3">Sort by:</span>
<select class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="sort-by">
    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
</select>

<script>
    document.getElementById('sort-by').addEventListener('change', function() {
        const sort = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sort);
        window.location.href = url.toString();
    });
</script>
