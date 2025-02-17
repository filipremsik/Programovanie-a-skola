<header>
    <div class="w-8/12 h-16 mx-auto bg-transparent font-manrope flex justify-between items-center">
        <div>
            <a href="/">
                <h1 class=" text-light-green text-2xl font-extrabold">uPhone</h1>
            </a>
        </div>
        <div id="menuClose" class="hidden lg:hidden">
            <span class="text-right text-light-green font-medium text-4xl cursor-pointer">&times;</span>
        </div>
        <div id="menuToggle" class="hidden max-lg:block lg:hidden">
            <span class="text-right text-light-green font-semibold cursor-pointer">&#9776;</span>
        </div>

        <div class="text-light-green flex justify-end items-center gap-x-4 text-center w-7/12 min-w-fit max-lg:hidden">
            <div class=" grid grid-cols-3 text-center w-7/12 min-w-fit max-w-sm">
                <a href="/" class="hover:font-bold cursor-pointer min-w-16">Domov</a>
                <a href="/shop" class="hover:font-bold cursor-pointer">Obchod</a>
                <a href="/about_us" class="hover:font-bold cursor-pointer">O nás</a>
            </div>
            <div id="search-bar" class="w-1/3">
                <input id="searchBarInput" type="text"
                    class=" border-2 w-full h-8 border-light-green rounded-3xl bg-transparent focus:outline-none text-center"
                    placeholder="Hľadať">
            </div>
            <div id="avatarIcon" href="/registration">
                <img src="{{ asset('img/svg/avatar.svg') }}" class=" cursor-pointer scale-150">
            </div>
            <div id="cart" class=" flex justify-center items-baseline">
                <form action="/cart" method="get">
                    @csrf

                    <button class=" pt-6" type="submit">
                        <img src='{{ asset('img/svg/cart.svg') }}' class=" scale-150">
                        @auth
                            <p id="numberOfCartItemAuth"
                                class=" relative bottom-2 left-2 bg-light-green text-dark-purple text-center rounded-full">
                                0
                            </p>
                        @else
                            <p id="numberOfCartItemZero"
                                class=" relative bottom-2 left-2 bg-light-green text-dark-purple text-center rounded-full">
                                0
                            </p>
                        @endauth
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="menu"
        class="hidden flex flex-col items-center text-center text-dark-purple bg-light-green gap-y-2 py-2 lg:hidden mb-10">
        <input id="searchBarInputSmall" type="text"
            class=" border-2 w-10/12 h-8 border-dark-purple rounded-3xl bg-transparent focus:outline-none text-center"
            placeholder="Hľadať">
        <a href="/" class="hover:font-bold cursor-pointer">Domov</a>
        <hr class="bg-dark-purple opacity-20 h-0.5 w-11/12" />
        <a href="/shop" class="hover:font-bold cursor-pointer">Obchod</a>
        <hr class="bg-dark-purple opacity-20 h-0.5 w-11/12" />
        <a href="/about_us" class="hover:font-bold cursor-pointer">O nás</a>
        <hr class="bg-dark-purple opacity-20 h-0.5 w-11/12" />
        @auth
            @cannot('temporary-login')
                <a href="/profile" class="hover:font-bold cursor-pointer">Profile</a>
                <hr class="bg-dark-purple opacity-20 h-0.5 w-11/12" />
                <a href="/logout" class="hover:font-bold cursor-pointer">Logout</a>
            @endcannot
            @can('temporary-login')
                <a href="/login" class="hover:font-bold cursor-pointer">Login</a>
                <hr class="bg-dark-purple opacity-20 h-0.5 w-11/12" />
                <a href="/registration" class="hover:font-bold cursor-pointer">Registration</a>
                <a href="/logout" class="hover:font-bold cursor-pointer">Logout</a>
            @endcan
        @else
            <a href="/login" class="hover:font-bold cursor-pointer">Login</a>
            <hr class="bg-dark-purple opacity-20 h-0.5 w-11/12" />
            <a href="/registration" class="hover:font-bold cursor-pointer">Registration</a>
        @endauth
        <hr class="bg-dark-purple opacity-20 h-0.5 w-11/12" />
        <a href="/cart" class="hover:font-bold cursor-pointer">Košík</a>
    </div>
    <div id="profileMenu" class="hidden flex w-8/12 h-0 justify-end mx-auto z-30">
        <div class="absolute w-fit -mr-6 mt-2">
            <div
                class="flex justify-between flex-col col-start-4 text-center w-full bg-light-green py-4 px-4 rounded-md">
                @auth
                    @cannot('temporary-login')
                        <p class="mb-2">Vitajte, {{ Auth::user()->login }} <span class=" font-bold"> </span> !</p>
                        @if (Auth::user()->admin)
                            <a href="/admin"
                                class=" w-32 px-2 border-dark-purple border-2 rounded-lg mb-2 hover:bg-dark-purple hover:text-light-green hover:font-bold">
                                Profile
                            </a>
                        @else
                            <a href="/profile"
                                class=" w-32 px-2 border-dark-purple border-2 rounded-lg mb-2 hover:bg-dark-purple hover:text-light-green hover:font-bold">
                                Profile
                            </a>
                        @endif

                        <a href="/logout"
                            class=" w-32 px-2 border-dark-purple border-2 rounded-lg hover:bg-dark-purple hover:text-light-green hover:font-bold">
                            Logout
                        </a>
                    @else
                        <a href="/login"
                            class=" w-32 px-2 border-dark-purple border-2 rounded-lg mb-2 hover:bg-dark-purple hover:text-light-green hover:font-bold">
                            Login
                        </a>
                        <a href="/registration"
                            class=" w-32 px-2 border-dark-purple border-2 rounded-lg hover:bg-dark-purple hover:text-light-green hover:font-bold">
                            Register
                        </a>
                    @endcannot
                @else
                    <a href="/login"
                        class=" w-32 px-2 border-dark-purple border-2 rounded-lg mb-2 hover:bg-dark-purple hover:text-light-green hover:font-bold">
                        Login
                    </a>
                    <a href="/registration"
                        class=" w-32 px-2 border-dark-purple border-2 rounded-lg hover:bg-dark-purple hover:text-light-green hover:font-bold">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
    </div>
    <div id="searchResults" class="hidden flex w-8/12 max-lg:w-6/12 h-0 justify-end mx-auto z-40">
        <div class="absolute w-fit mt-2">
            <div id="searchResultsHolder"
                class="flex justify-between max-h-60 overflow-auto flex-col col-start-4 text-center w-96 max-md:w-72 max-sm:w-60 bg-light-green py-4 px-4 rounded-md">
            </div>
        </div>
    </div>
</header>

<script src="{{ asset('js/navbar.js') }}"></script>
<script src="{{ asset('js/navigation_menu.js') }}"></script>
<script src="{{ asset('js/search_call.js') }}"></script>
