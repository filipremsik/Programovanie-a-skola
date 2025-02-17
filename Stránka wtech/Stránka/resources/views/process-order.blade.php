<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/cart.svg') }}">
    @vite('resources/css/app.css')
</head>

<body class=" text-manrope bg-gradient-to-b from-dark-purple to-black min-h-screen max-sm:h-full font-manrope min-w-80">
   
    <x-navbar />
    
    <main
        class="flex justify-center items-start h-fit gap-x-20 mx-auto mt-10 max-lg:gap-x-10 max-sm:flex-col max-sm:mt-12 max-sm:h-full">
        <div
            class="flex mb-10 flex-col items-center max-sm:mx-auto p-12 max-lg:p-6 text-light-green max-xl:w-5/12 max-sm:w-10/12 w-4/12 max-sm:mb-16 shadow-custom shadow-light-purple rounded-[3rem]">
            <div class="text-center mb-12 animate-jump-in max-sm:mb-2">
                <h1 class=" font-bold text-2xl mb-2 max-sm:text-lg">Faktúračné údaje</h1>
                <p class=" font-light max-sm:text-sm">Formulár obsahujúci všetky potrebné údaje na korektné odoslanie
                    objednávky!</p>
            </div>
            <form method="POST" action="/create-order"
                class="flex flex-col max-h-[55vh] max-lg:grid-cols-1 w-10/12 max-lg:w-full gap-x-12 overflow-auto">
                @csrf
                <input type="hidden" name="pickUpPayment" value="{{ $pickUpPayment }}">
                <input type="hidden" name="pickUp" value="{{ $pickUp }}">
                <div>
                    <label for="city">Krstné meno</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="first_name" name="first_name"
                        value="
                        @if (isset($user_data)) {{ $user_data['name'] }} @endif
                        "
                        tabindex="1">
                    @error('first_name')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="city">Priezvisko</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="last_name" name="last_name"
                        value="
                        @if (isset($user_data)) {{ $user_data['surname'] }} @endif
                        "
                        tabindex="2">
                    @error('last_name')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address">Adresa</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="address" name="address"
                        value="
                        @if (isset($user_data)) {{ $user_data['address'] }} @endif
                        "
                        tabindex="4">
                    @error('address')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email">Email</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="email"
                        id="email" name="email"
                        value="
                        @if (isset($user_data)) {{ $user_data['email'] }} @endif
                        "
                        tabindex="5">
                    @error('email')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="postal_code">PSČ</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="postal_code" name="postal_code"
                        value="
                        @if (isset($user_data)) {{ $user_data['postal_code'] }} @endif
                        "
                        tabindex="6">
                    @error('postal_code')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class=" col-start-2 max-lg:col-start-1">
                    <label for="phone">Tel. číslo</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="phone" name="phone"
                        value="
                        @if (isset($user_data)) {{ $user_data['phone_number'] }} @endif
                        ">
                    @error('phone')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-x-4 mt-8 max-lg:w-8/12 max-lg:mx-auto max-lg:mb-3 max-sm:flex-col max-sm:gap-y-4">
                    <button type="submit"
                        class="w-full h-8 bg-light-green font-bold text-center hover:bg-transparent hover:border-2 hover:border-light-green hover:text-light-green text-dark-purple rounded-xl transition-all">
                        OBJEDNAŤ
                    </button>
                    <a href="./shopping_cart.html"
                        class=" flex items-center justify-center w-full h-8 bg-light-green font-bold hover:bg-transparent hover:border-2 hover:border-light-green hover:text-light-green text-dark-purple rounded-xl transition-all">
                        SPÄŤ
                    </a>
                </div>
        </div>
        <aside
            class=" h-10/12 text-light-green shadow-custom shadow-light-purple rounded-3xl p-10 max-sm:mb-16 max-sm:mx-auto">
            <h2 class="text-3xl text-center font-semibold mb-4">
                Prepočet ceny
            </h2>
            <div class="grid grid-cols-2">
                <p>Cena bez DPH</p>
                <p class="text-right">{{ number_format($taxLessPrice, 2, ',', ' ') }} €</p>
                <p>Cena s DPH</p>
                <p class="text-right">{{ number_format($taxedPrice, 2, ',', ' ') }} €</p>
            </div>
            <hr class="mt-8 mb-4">
            <div class="grid grid-cols-2 font-bold mb-4">
                <p>Spolu</p>
                <p class="text-right">{{ number_format($totalPrice, 2, ',', ' ') }} €</p>
            </div>
            <button type="submit"
                class=" w-full py-2 bg-light-purple text-center rounded-full hover:bg-light-green hover:text-dark-purple hover:font-bold">
                Objednať
            </button>
        </aside>
        </form>
    </main>
</body>
<script src="{{ asset('js/trim_inputs.js') }}"></script>

</html>
