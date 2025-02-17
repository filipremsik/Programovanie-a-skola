<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/cart.svg') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-b min-h-screen from-dark-purple to-black font-manrope flex flex-col gap-16">
    <header class=" content-start">
        <x-navbar />
    </header>
    @if ($errors->has('500'))
        <div
            class="max-w-fit mx-auto z-10 absolute top-20 left-12 bg-light-green py-4 px-10 rounded-md animate-fade-down">
            <p>
                {{ $errors }}
            </p>
        </div>
    @elseif($errors->has('code'))
        <div
            class="max-w-fit mx-auto z-10 absolute top-20 left-12 bg-light-green py-4 px-10 rounded-md animate-fade-down">
            <p>
                Došlo k duplicite údajov - tel. číslo alebo login sú už registrované.
            </p>
        </div>
    @endif
    <main class="flex justify-center items-center h-full py-10">
        <div
            class="flex flex-col items-center p-12 max-lg:p-6 text-light-green max-h-fit max-lg:mt-4 max-xl:w-8/12 max-sm:w-10/12 w-6/12 mx-auto shadow-custom shadow-light-purple rounded-[3rem]">
            <div class="text-center mb-12 animate-jump-in max-lg:mb-0">
                <h1 class=" font-bold text-2xl mb-2 max-sm:text-lg max-lg:mb-0">Registračný formulár</h1>
                <p class=" font-light max-lg:hidden">Zjednodušte si nakupovanie a uchovajte si vaše informácie na ďalší
                    nákup!</p>
            </div>
            <form method="POST" action="/registration-submit"
                class="grid max-h-[60vh] grid-cols-2 max-lg:mt-4 max-lg:grid-cols-1 w-10/12 max-lg:w-full gap-x-12 overflow-auto">
                {{-- Security feature --}}
                @csrf

                <div class="col-start-1 row-start-1">
                    <label for="login">Login</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="login" name="login" value="{{ old('login') }}" tabindex="1" />
                    @error('login')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-start-2 max-lg:col-start-1">
                    <label for="first_name">Krstné meno</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="first_name" name="first_name" value="{{ old('first_name') }}" tabindex="4">
                    @error('first_name')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-start-2 max-lg:col-start-1">
                    <label for="last_name">Priezvisko</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="last_name" name="last_name" value="{{ old('last_name') }}" tabindex="4">
                    @error('last_name')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-start-1 row-start-2">
                    <label for="password">Heslo</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="password"
                        id="password" name="password" tabindex="2">
                    @error('password')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-start-2 max-lg:col-start-1">
                    <label for="address">Adresa</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="address" name="address" value="{{ old('address') }}" tabindex="5">
                    @error('address')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-start-1 row-start-3">
                    <label for="email">Email</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="email"
                        id="email" name="email" value="{{ old('email') }}" tabindex="3">
                    @error('email')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-start-2 max-lg:col-start-1">
                    <label for="postal_code">PSČ</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="postal_code" name="postal_code" value="{{ old('postal_code') }}" tabindex="6">
                    @error('postal_code')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-start-2 max-lg:col-start-1">
                    <label for="phone">Tel. číslo</label><br>
                    <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2" type="text"
                        id="phone" name="phone" value="{{ old('phone') }}" tabindex="7">
                    @error('phone')
                        <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-x-4 mt-8 max-lg:w-8/12 max-lg:mx-auto max-lg:mb-3 max-sm:flex-col max-sm:gap-y-4">
                    <button type="submit"
                        class="w-full h-8 bg-light-green font-bold text-center hover:bg-transparent hover:border-2 hover:border-light-green hover:text-light-green text-dark-purple rounded-xl transition-all">REGISTROVAŤ</button>
                    <a href="/login"
                        class=" flex items-center justify-center w-full h-8 bg-light-green font-bold hover:bg-transparent hover:border-2 hover:border-light-green hover:text-light-green text-dark-purple rounded-xl transition-all">
                        <span>OBCHOD</span>
                    </a>
                </div>
                <div class="flex justify-start items-end font-medium text-light-purple underline max-lg:justify-center">
                    <a href="/login" class="hover:text-light-green">Už som
                        zaregistrovaný/á</a><!--TODO Dorobiť link na login -->
                </div>
            </form>
        </div>
    </main>
</body>

</html>
