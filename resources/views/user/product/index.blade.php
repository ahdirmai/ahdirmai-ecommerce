<x-app-layout>
    <x-slot name="title">
        {{ __('Product Catalog') }}
    </x-slot>

    <section class="min-h-screen w-full flex items-center justify-center pt-12 pb-12 px-4">
        <div class="max-w-7xl mx-auto w-full">
            <div class="pt-8 px-4">
                <div class="bg-gray-100 py-2">
                    <div class="container mx-auto px-4">
                        <ul class="flex space-x-2 text-sm">
                            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                            <li class="text-gray-500">/</li>
                            <li class="text-gray-600">Products</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <section class="py-8">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Sidebar Filters -->
                        <div class="md:w-1/4 lg:w-1/5">
                            @include('user.product.include.side-filter')
                        </div>

                        <!-- Product Grid -->
                        <div class="md:w-3/4 lg:w-4/5">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                                <h2 class="text-2xl font-bold mb-4 sm:mb-0">Dashboard UI Kits</h2>

                                <div class="flex items-center">
                                    @include('user.product.include.sort-by')
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Product Card 1 -->
                                @forelse ($products as $product)
                                    <x-product-card :product="$product" />
                                @empty
                                    <div class="col-span-3 text-center">
                                        <p class="text-gray-500">No products found.</p>
                                    </div>
                                @endforelse


                            </div>

                            <!-- Pagination -->
                            <div class="flex justify-center mt-10">
                                {{ $products->links('vendor.pagination.tailwind') }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </section>
</x-app-layout>
