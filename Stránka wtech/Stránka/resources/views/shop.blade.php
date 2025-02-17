<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/cart.svg') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-black font-manrope min-w-80">
    <main class=" bg-gradient-to-b from-dark-purple to-black via-black h-full">
        
        <x-navbar />
        <div class="flex flex-col items-center mt-12 text-light-green">
            <h1 class=" font-bold text-5xl mb-8 max-sm:text-3xl">Katalóg produktov</h1>
            <p class=" font-light text-xl max-md:text-lg max-sm:text-md text-center">Všeobecný prehľad všetkých
                produktov, ktoré momentálne poskytujeme.</p>
        </div>

        <form id="productsDisplayStart" method="get" action="/shop" class="mt-12">
            <div
                class="flex items-center justify-center px-12 gap-x-6 max-sm:px-6 py-4 w-fit h-full mx-auto max-md:grid max-md:grid-cols-2 bg-light-purple text-light-green rounded-full max-md:rounded-3xl max-md:w-10/12 max-md:gap-y-6 max-md:gap-x-3">
                <div class="flex flex-col font-medium max-md:col-span-2 text-center">
                    <div class="flex justify-between w-full ">
                        <label for="maxPrice">Cena: </label>
                        <input id="inputPrice" name="maxPrice" type="number" min="0" max="{{ $maximal_price }}"
                            value="{{ $max_price }}"
                            class=" inputPrice bg-transparent text-right font-bold hover:cursor-pointer">
                        <span class=" font-bold">€</span>
                    </div>
                    <div>
                        <input type="range" id="sliderPrice" min="0" max="{{ $maximal_price }}" value="{{ $max_price }}"
                            class="rounded-3xl custom-slider sliderPrice">
                    </div>
                </div>

                <div class="grid grid-cols-3 items-start gap-x-2 gap-y-4 max-md:grid-cols-2 max-md:col-span-2">
                    @foreach ($unique_parameters as $key => $unique_parameter)
                        <div class="flex justify-center text-center max-md:flex-col">
                            <label for="category{{ $key }}">{{ ucfirst($unique_parameter->parameter) }} :
                            </label>
                            <select name="category{{ $key }}" id="category{{ $key }}"
                                class="bg-transparent font-bold text-light-green generatedSelect">
                                <option value="all" class="bg-dark-purple" selected>All</option>
                                @foreach ($all_parameters as $index => $element)
                                    @if ($element->parameter === $unique_parameter->parameter)
                                        <option value="{{ $element->value }}" class="bg-dark-purple"
                                            @if (count($all_query_parameters) > 1) @if ($element->value === $all_query_parameters[$key + 1]) 
                                                    selected @endif
                                            @endif>
                                            {{ ucfirst($element->value) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
                <img id="clearFilter" src="{{ asset('img/svg/filter_off.svg') }}" class=" mx-auto  cursor-pointer">
                <div class=" flex items-center justify-center mx-auto w-fit">
                    <button type="submit"
                        class="flex items-center justify-center p-2 max-md:col-span-2 rounded-full bg-light-green text-light-purple font-bold hover:scale-110 ">
                        <img src="{{ asset('img/svg/search.svg') }}">
                    </button>
                </div>
            </div>
            <div class="w-8/12 text-light-green mx-auto mt-10">
                <label for="order_by" class=" text-lg font-bold">Zoradiť podľa : </label>
                <select name="order_by" id="order_by" class="bg-transparent font-bold text-light-green">
                    <option value="" class="hidden bg-dark-purple"
                        @if ($order_by === '') selected @endif>
                        Vyberte zoradenie
                    </option>
                    <option value="ascPrice" class="bg-dark-purple" @if ($order_by === 'ascPrice') selected @endif>
                        Cena vzostupne
                    </option>
                    <option value="dscPrice" class="bg-dark-purple" @if ($order_by === 'dscPrice') selected @endif>
                        Cena zostupne
                    </option>
                </select>
            </div>

        </form>
        <div
            class="grid grid-cols-4 max-xl:grid-cols-3 max-lg:grid-cols-2 max-md:grid-cols-1 w-8/12 mx-auto gap-x-12 gap-y-8 max-md:gap-y-4 my-10 z-0">
            @foreach ($array_products as $index => $item)
                <x-shop-item :item="$item" :index="$index" />
            @endforeach
        </div>
        {{ $pagination->links() }}
        <x-footer />
    </main>
</body>

</html>
<script src="{{ asset('js/shop_filter.js') }}"></script>
