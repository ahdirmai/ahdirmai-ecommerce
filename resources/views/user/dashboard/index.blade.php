<x-app-layout>
    <x-slot name="title">
        {{ __('Dashboard') }}
    </x-slot>

    <section class="min-h-screen flex items-center pt-24 pb-12 px-4">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
            <div class="text-center md:text-left">
                <h1 class="text-3xl md:text-5xl font-extrabold mb-2 animate-fade-in">
                    Premium Digital Products
                </h1>
                <div class="w-20 h-1 bg-black dark:bg-white mb-4 animate-width mx-auto md:mx-0"></div>
                <h2 class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-4 animate-fade-in">
                    For Creators & Developers
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6 animate-fade-in max-w-md mx-auto md:mx-0">
                    Discover high-quality digital products that will elevate your projects. From UI kits to code
                    templates, we have everything you need.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 animate-fade-in">
                    <a href="#featured"
                        class="px-6 py-3 bg-black dark:bg-white text-white dark:text-black font-semibold rounded hover:-translate-y-1 transition text-center">
                        Explore Products
                    </a>
                    <a href="#categories"
                        class="px-6 py-3 border border-black dark:border-white font-semibold rounded hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition text-center">
                        Browse Categories
                    </a>
                </div>
            </div>
            <div class="animate-scale-in">
                <img src="https://picsum.photos/600/400.webp" alt="Digital Products Showcase"
                    class="rounded shadow-lg w-full" />
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-20 bg-gray-100 dark:bg-gray-800 dark-transition">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold">CATEGORIES</h2>
                <div class="w-16 h-1 bg-black dark:bg-white mx-auto mt-2"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($categories as $category)
                    <div class="bg-white dark:bg-gray-700 p-6 rounded shadow text-center dark-transition">
                        <i class="fas {{ $category['icon'] }} text-3xl mb-4 text-black dark:text-white"></i>
                        <h3 class="text-lg font-semibold mb-2">{{ $category['name'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $category['description'] }}</p>
                        <a href="{{ route('products.index', [
                            'categories' => $category->slug,
                        ]) }}"
                            class="text-black dark:text-white font-semibold hover:underline">
                            Explore <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section id="featured" class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold">FEATURED PRODUCTS</h2>
                <div class="w-16 h-1 bg-black dark:bg-white mx-auto mt-2"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($products as $product)
                    {{-- @include('partials.product-card', $product) --}}

                    <x-product-card :product="$product" />
                @endforeach
            </div>

            {{-- view all product --}}
            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}"
                    class="px-6 py-3 bg-black dark:bg-white text-white dark:text-black font-semibold rounded hover:-translate-y-1 transition">
                    View All Products
                </a>
            </div>
    </section>

    <!-- Testimonials Section -->
    {{-- <section id="testimonials" class="py-20 bg-gray-100 dark:bg-gray-800 dark-transition">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold">WHAT OUR CUSTOMERS SAY</h2>
                <div class="w-16 h-1 bg-black dark:bg-white mx-auto mt-2"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ([
        [
            'name' => 'John Smith',
            'role' => 'Frontend Developer',
            'avatar' => 'https://via.placeholder.com/50x50',
            'review' => 'The dashboard UI kit saved me weeks of design work. Definitely worth the investment!',
        ],
        [
            'name' => 'Sarah Johnson',
            'role' => 'UI/UX Designer',
            'avatar' => 'https://via.placeholder.com/51x51',
            'review' => 'Amazing quality and great customer support. Highly recommended for all developers!',
        ],
        [
            'name' => 'Mike Chen',
            'role' => 'Freelance Developer',
            'avatar' => 'https://via.placeholder.com/52x52',
            'review' => 'Professional quality resources that have helped me deliver better projects to my clients.',
        ],
    ] as $index => $testimonial)
                    <div
                        class="bg-white dark:bg-gray-700 p-6 rounded shadow dark-transition {{ $index === 2 ? 'md:col-span-2 lg:col-span-1' : '' }}">
                        <div class="text-yellow-500 mb-2">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <p class="italic text-gray-600 dark:text-gray-300 mb-4">
                            "{{ $testimonial['review'] }}"
                        </p>
                        <div class="flex items-center gap-4">
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}"
                                class="rounded-full w-12 h-12" />
                            <div>
                                <h4 class="font-semibold">{{ $testimonial['name'] }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $testimonial['role'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div> --}}
    {{-- </section> --}}
</x-app-layout>
