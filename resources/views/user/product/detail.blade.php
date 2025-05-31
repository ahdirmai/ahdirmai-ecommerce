<x-app-layout>
    <x-slot name="title">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="p-12">


        <!-- Product Detail Section -->
        <section class="py-6">
            {{-- <div class="bg-gray-100 px-4"> --}}
            <div class="container mx-auto px-4 lg:mb-4">
                <ul class="flex space-x-2 text-sm">
                    <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a></li>
                    <li class="text-gray-500">/</li>
                    <li><a href="#" class="text-blue-600 hover:underline">UI Kits</a></li>
                    <li class="text-gray-500">/</li>
                    <li class="text-gray-600">{{ ucfirst($product->name) }}</li>
                </ul>
            </div>
            {{-- </div> --}}
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Product Gallery -->
                    <div class="md:w-1/2">
                        <div class="bg-white p-4 rounded-lg shadow mb-4">
                            @php
                                $media = $product->getMedia('*');
                                $mainImage = $media->first();
                            @endphp
                            @if ($mainImage)
                                <img src="{{ $mainImage->getUrl() }}" alt="{{ $product->name }}" id="mainImage"
                                    class="rounded object-cover"
                                    style="width:700px; height:400px; max-width:100%; max-height:100%;" />
                            @else
                                <img src="https://via.placeholder.com/700x400?text=No+Image" alt="No Image"
                                    id="mainImage" class="rounded object-cover"
                                    style="width:700px; height:400px; max-width:100%; max-height:100%;" />
                            @endif
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach ($media as $index => $item)
                                <div class="thumbnail cursor-pointer border-2 {{ $index === 0 ? 'border-blue-500' : 'border-transparent hover:border-gray-300' }} rounded p-1"
                                    data-img="{{ $item->getUrl() }}">
                                    <img src="{{ $item->getUrl() }}" alt="Thumbnail {{ $index + 1 }}"
                                        class="w-full h-20 object-cover rounded" />
                                </div>
                            @endforeach
                            @if ($media->isEmpty())
                                <div class="thumbnail cursor-pointer border-2 border-blue-500 rounded p-1"
                                    data-img="https://via.placeholder.com/700x400?text=No+Image">
                                    <img src="https://via.placeholder.com/100x80?text=No+Image" alt="No Image"
                                        class="w-full h-20 object-cover rounded" />
                                </div>
                            @endif
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const mainImage = document.getElementById('mainImage');
                                const thumbnails = document.querySelectorAll('.thumbnail');
                                thumbnails.forEach(function(thumb) {
                                    thumb.addEventListener('click', function() {
                                        mainImage.src = this.getAttribute('data-img');
                                        thumbnails.forEach(function(t) {
                                            t.classList.remove('border-blue-500');
                                            t.classList.add('border-transparent');
                                        });
                                        this.classList.add('border-blue-500');
                                        this.classList.remove('border-transparent');
                                    });
                                });
                            });
                        </script>
                    </div>

                    <!-- Product Info -->
                    <div class="md:w-1/2">
                        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>


                        <div class="flex items-center mb-6">
                            <span class="text-2xl font-bold text-gray-900 mr-3">Rp. {{ $product->price }}</span>
                            {{-- <span class="text-lg text-gray-500 line-through mr-3">$79.99</span>
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">25% OFF</span> --}}
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-700">
                                {{ $product->description }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="flex">
                                <span class="text-gray-600 font-medium mr-2">Category:</span>
                                <span class="text-gray-800">{{ $product->category->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 font-medium mr-2">Format:</span>
                                <span class="text-gray-800">{{ $product->format }}</span>
                            </div>

                            <div class="flex">
                                <span class="text-gray-600 font-medium mr-2">File Size:</span>
                                <span class="text-gray-800">{{ $product->file_size }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 mb-8">
                            {{-- <div class="flex items-center border rounded-lg overflow-hidden">
                                <button
                                    class="quantity-btn minus bg-gray-100 px-3 py-2 text-lg font-medium hover:bg-gray-200">-</button>
                                <input type="number" value="1" min="1" max="10" id="productQuantity"
                                    class="w-12 text-center border-0 focus:ring-0" aria-label="Product Quantity" />
                                <button
                                    class="quantity-btn plus bg-gray-100 px-3 py-2 text-lg font-medium hover:bg-gray-200">+</button>
                            </div> --}}

                            <button
                                class="btn btn-primary add-to-cart-btn bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center"
                                id="addToCart" data-id="1">
                                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                            </button>

                            <button
                                class="btn btn-outline border border-blue-600 text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg font-medium flex items-center justify-center">
                                <i class="fas fa-bolt mr-2"></i> Buy Now
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-undo text-gray-500 mr-3"></i>
                                <span class="text-gray-700">30-Day Money Back Guarantee</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-lock text-gray-500 mr-3"></i>
                                <span class="text-gray-700">Secure Payment</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-headset text-gray-500 mr-3"></i>
                                <span class="text-gray-700">24/7 Customer Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Tabs Section -->
        <section class="py-6">
            <div class="container mx-auto px-4">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="border-b">
                        <div class="flex overflow-x-auto">
                            <button
                                class="tab-btn active px-6 py-4 font-medium text-blue-600 border-b-2 border-blue-600 whitespace-nowrap"
                                data-tab="description">
                                Description
                            </button>
                            <button
                                class="tab-btn px-6 py-4 font-medium text-gray-600 hover:text-blue-600 whitespace-nowrap"
                                data-tab="features">
                                Features
                            </button>
                            {{-- <button
              class="tab-btn px-6 py-4 font-medium text-gray-600 hover:text-blue-600 whitespace-nowrap"
              data-tab="reviews">
              Reviews
                </button>
                <button
              class="tab-btn px-6 py-4 font-medium text-gray-600 hover:text-blue-600 whitespace-nowrap"
              data-tab="faq">
              FAQ
                </button> --}}
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="tab-panel active" id="description">
                            {{ $product->long_description }}
                        </div>
                        <div class="tab-panel hidden" id="features">
                            <h3 class="text-xl font-bold mb-6">Key Features</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach ($product->details as $item)
                                    <div class="flex">
                                        <div
                                            class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center mr-4 flex-shrink-0">
                                            <i class="fas {{ $item->attribute_icon }}"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold mb-1 text-gray-800">{{ $item->attribute_name }}
                                            </h4>
                                            <p class="text-gray-700">
                                                {{ $item->attribute_value }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        {{-- <div class="tab-panel hidden" id="reviews">
                ... (reviews content)
            </div> --}}
                        {{-- <div class="tab-panel hidden" id="faq">
                ... (faq content)
            </div> --}}
                    </div>
                </div>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabButtons = document.querySelectorAll('.tab-btn');
                const tabPanels = document.querySelectorAll('.tab-panel');

                tabButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Remove active class from all buttons
                        tabButtons.forEach(b => {
                            b.classList.remove('active', 'text-blue-600', 'border-blue-600');
                            b.classList.add('text-gray-600');
                        });
                        // Hide all panels
                        tabPanels.forEach(panel => {
                            panel.classList.add('hidden');
                            panel.classList.remove('active');
                        });
                        // Activate clicked button
                        this.classList.add('active', 'text-blue-600', 'border-blue-600');
                        this.classList.remove('text-gray-600');
                        // Show corresponding panel
                        const tab = this.getAttribute('data-tab');
                        const panel = document.getElementById(tab);
                        if (panel) {
                            panel.classList.remove('hidden');
                            panel.classList.add('active');
                        }
                    });
                });
            });
        </script>

        <!-- Related Products Section -->
        {{-- <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold mb-2">RELATED PRODUCTS</h2>
                    <div class="w-16 h-1 bg-blue-600 mx-auto"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-lg shadow overflow-hidden transition-transform hover:scale-105">
                        <div class="relative">
                            <img src="https://picsum.photos/200/300" alt="Mobile App UI Kit"
                                class="w-full h-48 object-cover" />
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                                <button
                                    class="action-btn quick-view bg-white rounded-full w-10 h-10 flex items-center justify-center mr-2 hover:bg-gray-100"
                                    data-id="5">
                                    <i class="fas fa-eye text-gray-700"></i>
                                </button>
                                <button
                                    class="action-btn add-to-cart bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-100"
                                    data-id="5">
                                    <i class="fas fa-shopping-cart text-gray-700"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-blue-600 text-xs font-semibold mb-1">UI Kit</div>
                            <h3 class="font-bold mb-2"><a href="product-detail.html"
                                    class="hover:text-blue-600">Mobile
                                    App UI Kit</a></h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs mr-1">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="text-gray-500 text-xs">(7)</span>
                            </div>
                            <div class="font-bold text-gray-900">$39.99</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden transition-transform hover:scale-105">
                        <div
                            class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded inline-block absolute m-2">
                            New</div>
                        <div class="relative">
                            <img src="https://picsum.photos/200/300" alt="E-commerce Template"
                                class="w-full h-48 object-cover" />
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                                <button
                                    class="action-btn quick-view bg-white rounded-full w-10 h-10 flex items-center justify-center mr-2 hover:bg-gray-100"
                                    data-id="2">
                                    <i class="fas fa-eye text-gray-700"></i>
                                </button>
                                <button
                                    class="action-btn add-to-cart bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-100"
                                    data-id="2">
                                    <i class="fas fa-shopping-cart text-gray-700"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-blue-600 text-xs font-semibold mb-1">Template</div>
                            <h3 class="font-bold mb-2">
                                <a href="product-detail.html" class="hover:text-blue-600">E-commerce Website
                                    Template</a>
                            </h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs mr-1">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-gray-500 text-xs">(28)</span>
                            </div>
                            <div>
                                <span class="font-bold text-gray-900 mr-2">$49.99</span>
                                <span class="text-gray-500 text-sm line-through">$69.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden transition-transform hover:scale-105">
                        <div class="relative">
                            <img src="https://picsum.photos/200/300" alt="Admin Dashboard Template"
                                class="w-full h-48 object-cover" />
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                                <button
                                    class="action-btn quick-view bg-white rounded-full w-10 h-10 flex items-center justify-center mr-2 hover:bg-gray-100"
                                    data-id="9">
                                    <i class="fas fa-eye text-gray-700"></i>
                                </button>
                                <button
                                    class="action-btn add-to-cart bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-100"
                                    data-id="9">
                                    <i class="fas fa-shopping-cart text-gray-700"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-blue-600 text-xs font-semibold mb-1">Template</div>
                            <h3 class="font-bold mb-2">
                                <a href="product-detail.html" class="hover:text-blue-600">Admin Dashboard Template</a>
                            </h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs mr-1">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-gray-500 text-xs">(15)</span>
                            </div>
                            <div>
                                <span class="font-bold text-gray-900 mr-2">$44.99</span>
                                <span class="text-gray-500 text-sm line-through">$59.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden transition-transform hover:scale-105">
                        <div class="relative">
                            <img src="https://picsum.photos/200/300" alt="UI Components Library"
                                class="w-full h-48 object-cover" />
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                                <button
                                    class="action-btn quick-view bg-white rounded-full w-10 h-10 flex items-center justify-center mr-2 hover:bg-gray-100"
                                    data-id="10">
                                    <i class="fas fa-eye text-gray-700"></i>
                                </button>
                                <button
                                    class="action-btn add-to-cart bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-100"
                                    data-id="10">
                                    <i class="fas fa-shopping-cart text-gray-700"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-blue-600 text-xs font-semibold mb-1">UI Kit</div>
                            <h3 class="font-bold mb-2"><a href="product-detail.html" class="hover:text-blue-600">UI
                                    Components Library</a></h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs mr-1">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="text-gray-500 text-xs">(9)</span>
                            </div>
                            <div class="font-bold text-gray-900">$34.99</div>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
    </div>

</x-app-layout>
