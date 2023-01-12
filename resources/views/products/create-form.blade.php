@extends('home')

@section('content')

<header>
    <h3 class="font-semibold text-xl text-center">{{ __('Add a product') }}</h3>
</header>

<form class="flex flex-col gap-2 mt-2" action="{{ route('products.store') }}" method="POST">
    @csrf

    <label class="text-xs" for="name">{{ __('Name') }}</label>
    <input class="border text-xs sm:text-sm p-2 shadow" type="text" name="name" id="name">
    @if ($errors->get('name'))
    @foreach ($errors->get('name') as $error)
    <p class="text-xs text-red-600 font-semibold">{{ $error }}</p>
    @endforeach
    @endif

    <label class="text-xs" for="price">{{ __('Price') }}</label>
    <input class="border text-xs sm:text-sm p-2 shadow" type="text" name="price" id="price">
    @if ($errors->get('price'))
    @foreach ($errors->get('price') as $error)
    <p class="text-xs text-red-600 font-semibold">{{ $error }}</p>
    @endforeach
    @endif

    <button class="self-end bg-green-400 shadow uppercase text-xs px-3 py-2" type="submit">
        {{ __('Submit') }}
    </button>
</form>

@endsection