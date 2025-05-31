<!-- Footer -->
<footer class="bg-black dark:bg-gray-900 text-white py-10 dark-transition">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="sm:col-span-2 lg:col-span-1">
            <h3 class="text-lg font-bold mb-4">DigitalHub</h3>
            <p class="text-sm text-gray-300 mb-4">
                Premium digital products for creators and developers.
            </p>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-gray-300 transition-colors">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="hover:text-gray-300 transition-colors">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="hover:text-gray-300 transition-colors">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="hover:text-gray-300 transition-colors">
                    <i class="fab fa-github"></i>
                </a>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-4">Categories</h3>
            <ul class="space-y-2 text-sm text-gray-300">
                @foreach (['UI Kits & Templates', 'Code & Scripts', 'Graphics & Media', 'E-books & Courses'] as $category)
                    <li>
                        <a href="#" class="hover:text-white transition-colors">
                            {{ $category }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-4">Quick Links</h3>
            <ul class="space-y-2 text-sm text-gray-300">
                @foreach ([
        'Home' => route('dashboard'),
        'Featured Products' => '#featured',
        'New Arrivals' => '#new-arrivals',
        'Special Offers' => '#',
        'Blog' => '#',
    ] as $title => $url)
                    <li>
                        <a href="{{ $url }}" class="hover:text-white transition-colors">
                            {{ $title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-4">Support</h3>
            <ul class="space-y-2 text-sm text-gray-300">
                @foreach ([
        'Contact Us' => '#',
        'FAQs' => '#',
        'Shipping & Returns' => '#',
        'Privacy Policy' => '#',
        'Terms of Service' => '#',
    ] as $title => $url)
                    <li>
                        <a href="{{ $url }}" class="hover:text-white transition-colors">
                            {{ $title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-10 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} DigitalHub. All rights reserved.
    </div>
</footer>
