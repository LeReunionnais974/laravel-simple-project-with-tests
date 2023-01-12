@extends('home')

@section('content')

<div class="flex flex-col gap-2">
    <h6>{{ ('General informations') }}</h6>

    <div class="px-3 py-2 shadow bg-white">
        <p class="text-xs sm:text-sm">{{ ('Product name :') }} {{ $product->name }}</p>
        <p class="text-xs sm:text-sm">{{ ('Product price :') }} {{ $product->price }}</p>
    </div>

    <h6>{{ ('Update informations') }}</h6>

    <div class="px-3 py-2 shadow bg-white">
        @include('products.partials.update-form')
    </div>

    <h6>{{ ('Delete product') }}</h6>

    <div class="px-3 py-2 shadow bg-white">
        @include('products.partials.delete-form')
    </div>
</div>

@endsection