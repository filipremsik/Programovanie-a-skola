<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/cart.svg') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-black font-manrope min-w-80">
    <main class=" bg-gradient-to-b from-dark-purple to-black via-black h-full">

        <x-navbar />

        <div
            class="max-sm:grid-cols-1 max-sm:gap-y-4 w-7/12 max-lg:w-8/12 max-md:w-10/12 mx-auto my-20 text-center text-light-green">
            <a href="/about_us">
                <div class="flex justify-center items-center h-fit min-h-48 max-sm:h-24 w-10/12 max-xl:w-11/12 bg-light-purple mx-auto rounded-3xl"
                    style="background-image: url('{{ asset('img/kolektiv.jpg') }}'); background-size: 100%;">
                    <button
                        class="bg-light-purple text-light-green font-bold text-lg text-center w-fit h-12 py-2 px-10 rounded-3xl mx-auto hover:bg-light-green hover:text-light-purple cursor-pointer">
                        O nás
                    </button>
                </div>
            </a>
        </div>
        <div class="flex flex-col items-center mt-12 text-light-green">
            <h1 class=" font-bold text-5xl mb-8 max-sm:text-3xl max-sm:text-center">Populárne Apple produkty</h1>

        </div>



        <div
            class="grid grid-cols-4 max-xl:grid-cols-3 max-lg:grid-cols-2 max-md:grid-cols-1 w-8/12 mx-auto gap-x-12 gap-y-16 max-md:gap-y-4 my-20">

            @foreach ($apple as $index => $item)
                <x-shop-item :item="$item" :index="$index" />
            @endforeach
        </div>



        </div>
        <div class="flex flex-col items-center mt-12 text-light-green">
            <h1 class=" font-bold text-5xl mb-8 max-sm:text-3xl max-sm:text-center">Populárne Android produkty</h1>
        </div>



        <div
            class="grid grid-cols-4 max-xl:grid-cols-3 max-lg:grid-cols-2 max-md:grid-cols-1 w-8/12 mx-auto gap-x-12 gap-y-16 max-md:gap-y-4 my-20">
            @foreach ($android as $index => $item)
                <x-shop-item :item="$item" :index="$index" />
            @endforeach
        </div>
        <div class="flex flex-col items-center mt-12 text-light-green">
            <h1 class=" font-bold text-5xl mb-8 max-sm:text-3xl">Nové produkty</h1>
        </div>

        <div
            class="grid grid-cols-2 max-xl:grid-cols-2 max-lg:grid-cols-2 max-md:grid-cols-1 lg:w-6/12 md:w-7/12 w-8/12 mx-auto gap-x-12 gap-y-16 max-md:gap-y-4 my-20 mx">

            @foreach ($news as $index => $item)
                <div class="flex flex-col">
                    <!--TODO upraviť linky na základe parametra-->
                    <a href="/single-page/{{ $item['id'] }}"
                        class=" w-fit h-full flex flex-col justify-between items-center shadow-custom shadow-purple mx-auto rounded-[2.5rem]">
                        <p class="text-white font-medium mt-2 px-6 text-lg text-center">{{ $item['name'] }}</p>
                        <img src="{{ asset('storage/' . $item['image']) }}" class=" mb-8 scale-[80%] rounded-2xl">
                    </a>
                    <form action="/cart" method="get" class="mx-auto">
                        @csrf
                        <input class="hidden" name="price" id="price"
                            value="{{ number_format($item['price'], 2) }}">
                        <input class="hidden" name="quantity" id="quantity" value="1">
                        <input class="hidden" name="product_id" id="product_id" value="{{ $item['id'] }}">
                        <button id="priceChange{{ $index }}" type="submit"
                            class=" relative bottom-6 bg-light-purple text-light-green font-bold text-lg text-center w-32 py-2 rounded-3xl mx-auto hover:bg-light-green hover:text-light-purple cursor-pointer">
                            {{ number_format($item['price'], 2) }} €
                        </button>
                    </form>
                </div>
            @endforeach

        </div>
    </main>
    <x-footer />
</body>

</html>
