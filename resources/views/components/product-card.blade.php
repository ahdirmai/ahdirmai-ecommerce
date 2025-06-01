<!-- Product Card Component -->
<div
    class="bg-white dark:bg-gray-700 border dark:border-gray-600 rounded shadow overflow-hidden dark-transition hover:shadow-lg transition-shadow">
    <div class="relative">
        {{-- <span
            class="absolute top-3 left-3 bg-black dark:bg-white text-white dark:text-black text-xs font-semibold px-3 py-1 rounded-full">
            Featured
        </span> --}}
        <img src="{{ $product->getFirstMediaUrl('*') }}" alt="{{ $product->name }}" class="w-full h-48 object-cover" />
    </div>

    <div class="p-4">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{-- href to  route('product.show',$product->slug) --}}
            {{ $product->category->name }}
            {{-- badge pysical or Digital --}}
            @if ($product->product_type === 'physical')
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">Physical</span>
            @else
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Digital</span>
            @endif

        </div>
        <h3 class="font-semibold text-lg leading-tight my-1">

            <a href="{{ route('products.show', $product->slug) }}" class="hover:underline">{{ $product->name }}</a>
        </h3>

        {{-- <div class="text-yellow-500 flex items-center gap-1 text-sm">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= floor($product['rating']))
                    <i class="fas fa-star"></i>
                @elseif($i - 0.5 <= $product['rating'])
                    <i class="fas fa-star-half-alt"></i>
                @else
                    <i class="far fa-star"></i>
                @endif
            @endfor
            <span class="text-gray-400 ml-2">({{ $product['reviews'] }})</span>
        </div> --}}

        <div class="flex items-center gap-2 mt-2">
            <span class="text-black dark:text-white font-semibold">
                Rp. {{ number_format($product['price'], 2) }}
            </span>
            @if (isset($product['old_price']))
                <span class="line-through text-gray-400">
                    ${{ number_format($product['old_price'], 2) }}
                </span>
            @endif
        </div>
    </div>
</div>
