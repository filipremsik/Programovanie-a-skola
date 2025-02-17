<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About us</title>
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
    @endif
    <main class="flex flex-col justify-center items-center h-full">
        <div class="w-3/4 flex flex-col justify-center items-center mb-20">
            <h1 class=" font-bold text-light-green text-4xl mb-10">O nás</h1>
            <div class="flex max-lg:flex-col gap-10">
                <p class=" text-light-green">
                    Samozrejme! Tu je popis nového malého eshopu zameraného výhradne na mobilné technológie:
                    <br><br>
                    Vitajte v MobileTechLand, vašom novom útočisku pre všetko, čo sa týka mobilných technológií! Sme
                    malý,
                    ale
                    vášnivý tím technologických nadšencov, ktorí sa spojili s jediným cieľom: priniesť vám najnovšie a
                    najlepšie
                    mobilné zážitky priamo do vašich rúk.
                    <br><br>
                    Naša misia je jednoduchá: ponúknuť našim zákazníkom najvyššiu kvalitu a najnovšie produkty z oblasti
                    mobilných technológií za dostupné ceny. Nezáleží na tom, či ste nadšený fanúšik najnovších modelov
                    smartfónov, vášnivý fotograf, ktorý hľadá tie najlepšie fotoaparáty, alebo technologický dobrodruh,
                    ktorý
                    túži po objavení najnovších trendov v oblasti gadgetov - MobileTechLand je tu pre vás!
                    <br><br>
                    V našom sortimente nájdete širokú škálu produktov od popredných značiek, vrátane najnovších modelov
                    smartfónov, tabletov, inteligentných hodiniek, slúchadiel, powerbankov, príslušenstva a ďalších
                    technologických doplnkov. Neustále sledujeme trendy a inovácie v oblasti mobilných technológií, aby
                    sme
                    vám
                    mohli ponúknuť len to najlepšie, čo trh má ponúknuť.
                </p>
                <img src={{ asset('img/kolektiv.jpg') }} class="w-1/2 h-1/2 rounded-2xl max-lg:w-full">
            </div>
        </div>
    </main>
    <x-footer />
</body>

</html>
