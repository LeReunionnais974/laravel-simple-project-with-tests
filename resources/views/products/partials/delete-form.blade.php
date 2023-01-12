<form class="flex flex-col gap-2 mt-2" action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
    @csrf
    @method('DELETE')

    <h2 class="text-xs sm:text-sm">
        {{ __('Are you sure you want to delete this product?') }}
    </h2>

    <button class="self-end bg-red-400 shadow uppercase text-xs px-3 py-2" type="submit">
        {{ __('Delete') }}
    </button>
</form>