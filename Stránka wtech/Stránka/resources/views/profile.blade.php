<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/cart.svg') }}">
    @vite('resources/css/app.css')
</head>

<body class=" bg-gradient-to-b from-dark-purple to-black via-black h-screen font-manrope min-w-80">
   
    <x-navbar />
    
    <main class="flex max-lg:items-center w-8/12 mx-auto mt-10 max-lg:flex-col max-sm:w-10/12">
        <aside
            class=" w-fit min-w-48 h-fit flex flex-col max-lg:mb-8 max-lg:gap-x-4 max-lg:flex-row max-lg:w-fit max-lg:items-center py-4 px-8 text-light-green shadow-custom shadow-purple rounded-2xl gap-y-4">
            <h3 class=" text-center font-bold text-2xl max-lg:hidden">Menu</h3>
            <div class="flex justify-between items-center">
                <a href="/profile" class="text-md hover:font-bold"> Objednávky </a>
                <img src="{{ asset('img/svg/box.svg') }}" class="max-lg:hidden">
            </div>
            @auth
                @can('admin')
                    <hr class="max-lg:hidden">
                    <div class="flex justify-between items-center max-lg:justify-center">
                        <a href="/admin" class="text-md hover:font-bold"> Produkty </a>
                        <img src="{{ asset('img/svg/inventory.svg') }}" class="max-lg:hidden">
                    </div>
                @endcan
            @endauth
            <a href="/logout"
                class="w-full h-fit text-center border-2 mt-4 px-4 py-2 max-lg:py-0 max-lg:mt-0 rounded-full hover:bg-light-green hover:font-extrabold hover:text-dark-purple">Odhlásiť</a>
        </aside>
        <div class="w-full px-12 mx-auto flex flex-col gap-2 min-w-[46rem] max-lg:px-0 max-lg:min-w-0">
            <h1 class="text-center text-4xl text-light-green font-medium mb-10 max-md:mb-2 max-sm:text-3xl">Všetky
                objednávky</h1>
            @foreach ($orders as $index => $order)
                <div class="h-fit">
                    <div
                        class="flex justify-between items-center h-10 px-4 text-light-green bg-light-purple rounded-full z-0">
                        <div class="flex gap-2">
                            <img id="additionalInfoButton{{ $index }}" src="{{ asset('img/svg/expand.svg') }}"
                                class="scale-100 cursor-pointer">
                            <img id="additionalInfoCloseButton{{ $index }}"
                                src="{{ asset('img/svg/insert.svg') }}" class="scale-100 cursor-pointer hidden">
                            <h3 class=" max-sm:text-sm">Objednávka č. {{ $index + 1 }}</h3>
                        </div>
                        <div class="flex justify-center items-center gap-4 max-sm:gap-2">
                            <p class="max-lg:hidden">{{ date_format($order->created_at, 'd.m.20y') }}</p>
                            <p class="max-lg:hidden">{{ $order->delivery_method }}</p>
                            <div
                                class=" w-fit h-fit px-2 max-sm:px-0 items-center @if ($order->status == 1) bg-green-600 @else bg-yellow-600 @endif rounded-full">
                                @if ($order->status == 0)
                                    <img src="{{ asset('img/svg/three_dots.svg') }}" class="scale-75">
                                @elseif($order->status == 1)
                                    <img src="{{ asset('img/svg/done.svg') }}" class="scale-75">
                                @endif
                            </div>
                            <p class=" font-bold max-sm:text-sm max-sm:w-full">
                                {{ number_format($shopping_sessions[$index]->total, 2, ',', ' ') }}€
                            </p>
                        </div>
                    </div>
                    <div id="additionalInfo{{ $index }}"
                        class=" grid grid-cols-2 h-fit pt-8 pb-4 px-4 text-dark-purple bg-light-green relative bottom-5 -z-10 rounded-b-2xl hidden">
                        <ul class="flex flex-col w-10/12">
                            <li class="flex justify-between mb-2 max-lg:flex-col">
                                <h3 class=" font-bold">Číslo objednávky: <span>{{ $index + 1 }}</span></h3>
                            </li>
                            <li class="flex justify-between mb-2 max-lg:flex-col">
                                Dátum objednávky: <span
                                    class="font-bold">{{ date_format($order->created_at, 'd.m.20y') }}</span>
                            </li>
                            <li class="flex justify-between mb-2 max-lg:flex-col">
                                Doprava: <span class="font-bold">{{ $order->delivery_method }}</span>
                            </li>
                            <li class="flex justify-between mb-2 max-lg:flex-col">
                                Stav objednávky: <span class="font-bold">
                                    @if ($order->status == 0)
                                        Čaká na spracovanie
                                    @elseif($order->status == 1)
                                        Odoslaná
                                    @endif
                                </span>
                            </li>
                        </ul>
                        <ul>
                            <input type="hidden" value="{{ $sum_of_order = 0 }}">
                            @foreach ($cart_items as $cart_item)
                                @foreach ($cart_item as $index_item => $inner_item)
                                    @if ($inner_item->session_id == $shopping_sessions[$index]->id)
                                        <li class="flex justify-between  max-sm:flex-col">{{ $inner_item->quantity }}x
                                            {{ $products[$index_item]->name }}
                                            <span
                                                class=" font-bold">{{ number_format($products[$index_item]->price * $inner_item->quantity, 2, ',', ' ') }}
                                                €</span>
                                            <input type="hidden"
                                                value="{{ $sum_of_order += $products[$index_item]->price * $inner_item->quantity }}">
                                        </li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>
                        <ul class=" col-start-2">
                            <li class="flex justify-between">Sumár ceny: <span
                                    class=" font-bold">{{ number_format($sum_of_order, 2, ',', ' ') }}€</span></li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
    <script src="{{ asset('js/additional_info.js') }}"></script>
</body>


</html>
