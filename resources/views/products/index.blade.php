@extends('home')

@section('content')

<header>

    <div class="flex items-center justify-between">
        <h3 class="font-semibold text-xl">{{ __('Products') }}</h3>
        <a href="{{ route('products.create') }}">
            <button class="bg-blue-400 shadow uppercase text-xs px-3 py-2">
                {{ __('Add product') }}
            </button>
        </a>
    </div>

</header>

<div class="grid grid-cols-1 gap-2 mt-4">

    @forelse ($products as $product)
    <div class="flex items-center justify-between px-3 py-2 shadow bg-white">
        <div>
            <p class="text-xs sm:text-sm">{{ $product->name }}</p>
            <p class="text-xs sm:text-sm">{{ $product->price }}</p>
        </div>
        <a class="text-xs hover:underline" href="{{ route('products.show', ['product' => $product->id]) }}">
            {{ __('Show more...') }}
        </a>
    </div>
    @empty
    <div class="px-3 py-2 shadow bg-white">
        <p class="text-xs sm:text-sm">{{ __('No products found') }}</p>
    </div>
    @endforelse

    {{ $products->links('pagination::tailwind') }}

</div>

@endsection