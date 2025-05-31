<x-app-layout>
    <x-slot name="title">
        {{ __('Product Catalog') }}
    </x-slot>

    <section class="min-h-screen w-full flex items-center justify-center pt-12 pb-12 px-4">
        <div>

            <div class="pt-8 px-4">
                <div class="bg-gray-100 py-2">
                    <div class="container mx-auto px-4">
                        <ul class="flex space-x-2 text-sm">
                            <li><a href="index-store.html" class="text-blue-600 hover:underline">Home</a></li>
                            <li class="text-gray-500">/</li>
                            <li class="text-gray-600">UI Kits</li>
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
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h3 class="font-bold text-lg mb-4">Filters</h3>

                                <!-- Categories -->
                                <div class="mb-6">
                                    <h4 class="font-medium mb-3 text-gray-800">Categories</h4>
                                    <ul class="space-y-2">
                                        <li>
                                            <a href="#" class="text-blue-600 hover:underline">All UI Kits</a>
                                        </li>
                                        <li>
                                            <a href="#" class="text-gray-700 hover:underline">Dashboard Kits</a>
                                        </li>
                                        <li>
                                            <a href="#" class="text-gray-700 hover:underline">Mobile UI Kits</a>
                                        </li>
                                        <li>
                                            <a href="#" class="text-gray-700 hover:underline">Web Templates</a>
                                        </li>
                                        <li>
                                            <a href="#" class="text-gray-700 hover:underline">Admin Panels</a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Price Range -->
                                <div class="mb-6">
                                    <h4 class="font-medium mb-3 text-gray-800">Price Range</h4>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600">$0</span>
                                        <span class="text-sm text-gray-600">$200</span>
                                    </div>
                                    <input type="range" min="0" max="200" value="100"
                                        class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer">
                                    <div class="flex justify-between mt-2">
                                        <span class="text-sm font-medium">$0 - $100</span>
                                    </div>
                                </div>

                                <!-- File Formats -->
                                <div class="mb-6">
                                    <h4 class="font-medium mb-3 text-gray-800">File Formats</h4>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                                            <span class="ml-2 text-gray-700">Figma</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                                            <span class="ml-2 text-gray-700">Sketch</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                                            <span class="ml-2 text-gray-700">Adobe XD</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- License Type -->
                                <div class="mb-6">
                                    <h4 class="font-medium mb-3 text-gray-800">License Type</h4>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="license"
                                                class="form-radio h-4 w-4 text-blue-600">
                                            <span class="ml-2 text-gray-700">Standard</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="license"
                                                class="form-radio h-4 w-4 text-blue-600">
                                            <span class="ml-2 text-gray-700">Extended</span>
                                        </label>
                                    </div>
                                </div>

                                <button
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium">
                                    Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Product Grid -->
                        <div class="md:w-3/4 lg:w-4/5">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                                <h2 class="text-2xl font-bold mb-4 sm:mb-0">Dashboard UI Kits</h2>

                                <div class="flex items-center">
                                    <span class="text-gray-600 mr-3">Sort by:</span>
                                    <select
                                        class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option>Popularity</option>
                                        <option>Newest</option>
                                        <option>Price: Low to High</option>
                                        <option>Price: High to Low</option>
                                        <option>Rating</option>
                                    </select>
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
                                <nav class="flex items-center space-x-2">
                                    <a href="#"
                                        class="px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                    <a href="#" class="px-3 py-1 rounded-lg bg-blue-600 text-white">1</a>
                                    <a href="#"
                                        class="px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">2</a>
                                    <a href="#"
                                        class="px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">3</a>
                                    <span class="px-3 py-1 text-gray-600">...</span>
                                    <a href="#"
                                        class="px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">8</a>
                                    <a href="#"
                                        class="px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </section>
</x-app-layout>
