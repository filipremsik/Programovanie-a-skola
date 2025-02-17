<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/cart.svg') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-black font-manrope min-w-80">
    <main class=" bg-gradient-to-b from-dark-purple to-black via-black h-full">

        <x-navbar />

        <div class="mt-4 w-full">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-3xl text-center">{{ $product->name }}</h1>
            <!-- Image gallery -->
            <div class="flex w-4/12 justify-center items-center max-w-fit mx-auto mt-8 gap-8 max-lg:flex-col">
                @foreach ($images as $image)
                    <img src="{{ asset('storage/' . $image->link) }}" alt="obrazok1"
                        class="h-full w-full object-cover object-center rounded-3xl max-h-[40vh]">
                @endforeach
            </div>

            <!-- Product info -->
            <div class="grid grid-cols-2 gap-x-10 mt-10 w-8/12 mx-auto max-lg:grid-cols-1 mb-10">
                <div class="text-white">
                    <h2 class="text-2xl mb-4 font-semibold sm:text-3xl">Popis produktu</h2>
                    <!-- Description and details -->
                    <p class="text-base">
                        {{ $product->description }}
                    </p>
                </div>
                <!-- Options -->
                <form action="/cart" class=" text-white max-lg:mt-10">
                    <h2 class="text-2xl mb-4 font-semibold text-white sm:text-3xl">Cena</h2>
                    <h3 class="text-4xl font-bold mb-8 max-lg:text-ce">{{ number_format($product->price, 2) }} €</h3>
                    <div class="flex gap-x-4 w-fit mb-4 max-sm:flex-col max-sm:gap-y-4">
                        <input class="hidden" name="price" value="{{ $product->price }}">
                        <input class="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mx-auto my-auto">
                            <button id="decrementQuantity" type="button"
                                class="bg-light-green text-dark-purple font-bold w-6 rounded-l-2xl -mr-[0.15rem]">-</button>
                            <input type="number" name="quantity" id="quantityValue" min="1" max="100"
                                value="1" class=" appearance-none bg-transparent text-center focus:outline-none">
                            <button id="incrementQuantity" type="button"
                                class="bg-light-green text-dark-purple font-bold w-6 rounded-r-2xl -ml-[1.15rem]">+</button>
                        </div>
                        <button type="submit"
                            class="bg-light-purple text-light-green font-bold text-md text-center w-fit h-fit py-2 px-10 rounded-3xl mx-auto hover:bg-light-green hover:text-light-purple cursor-pointer">
                            Pridať do košíka
                        </button>
                    </div>
                    <div class="mt-10">
                        <h3 class="text-2xl font-semibold mb-2">Parameters</h3>
                        <hr class="mb-2">
                        @foreach ($parameters as $index => $parameter_array)
                            @if ($index === 0)
                                <p class="text-xl font-semibold">{{ ucfirst($parameter_array->parameter) }} :
                            @endif

                            @if ($index === 0 || $temp_param === $parameter_array->parameter)
                                <span class="text-lg font-light">{{ ucfirst($parameter_array->value) }} &nbsp; |
                                    &nbsp; </span>
                            @else
                                </p>
                                <p class="text-xl font-semibold">{{ ucfirst($parameter_array->parameter) }} :
                                    @if ($index === count($parameters) - 1)
                                        <span class="text-lg font-light">{{ ucfirst($parameter_array->value) }} &nbsp;
                                            |
                                            &nbsp; </span>
                                    @endif
                            @endif

                            <span class="hidden">{{ $temp_param = $parameter_array->parameter }}</span>
                        @endforeach
                    </div>
            </div>
    </main>
</body>
<script src="{{ asset('js/quantity_change.js') }}"></script>

</html>
