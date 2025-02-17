<div class="flex flex-col">
    <!--TODO upraviť linky na základe parametra-->
    <a href="/single-page/{{ $item['id'] }}"
        class=" w-fit h-full flex flex-col justify-between items-center shadow-custom shadow-purple mx-auto rounded-[2.5rem]">
        <p class="text-white font-medium mt-2 px-6 text-lg text-center">{{ $item['name'] }}</p>
        <img src="{{ asset('storage/' . $item['image']) }}" class=" mb-8 scale-[80%] rounded-2xl">
    </a>
    <form action="/cart" method="get" class="mx-auto">
        @csrf
        <input class="hidden" name="price" id="price" value="{{ number_format($item['price'], 2) }}">
        <input class="hidden" name="quantity" id="quantity" value="1">
        <input class="hidden" name="product_id" id="product_id" value="{{ $item['id'] }}">
        <button id="priceChange{{ $index }}" type="submit"
            class=" relative bottom-6 bg-light-purple text-light-green font-bold text-lg text-center w-32 py-2 rounded-3xl mx-auto hover:bg-light-green hover:text-light-purple cursor-pointer">
            {{ number_format($item['price'], 2) }} €
        </button>
    </form>
</div>
