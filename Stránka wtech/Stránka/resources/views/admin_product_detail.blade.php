<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin zóna</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/cart.svg') }}">
    @vite('resources/css/app.css')

</head>

<body class="bg-gradient-to-b min-h-screen from-dark-purple to-black via-black font-manrope min-w-80">
    <header>
        <x-navbar />
    </header>
    <main class="flex max-lg:items-center w-8/12 mx-auto mt-10 max-lg:mt-0 max-lg:flex-col max-sm:w-10/12">
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
        <div class="mx-auto w-8/12 h-fit max-lg:w-9/12 max-sm:w-full mb-10">
            <h1 class="text-center text-3xl font-bold text-light-green mb-10">Detail produktu</h1>
            <form id="updateProducts" name="updateProducts" action="/product/update" method="post"
                enctype="multipart/form-data"
                class="grid grid-cols-2 max-lg:grid-cols-1 w-full h-full p-6 gap-x-6 gap-y-0 max-md:w-full shadow-custom shadow-purple text-light-green rounded-2xl">
                @csrf
                @if (isset($product))
                    @method('PUT')
                @else
                    @method('POST')
                @endif
                <div>
                    <input name="product_id" type="hidden"
                        value="@if (isset($product)) {{ $product->id }} @endif">
                    <div>
                        <label for="product_name">Meno produktu</label><br>
                        <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-3 mb-2" type="text"
                            id="product_name" name="product_name"
                            value=" 
                            @if (isset($product)) {{ $product->name }} @else {{old('name')}} @endif"
                            tabindex="1" />
                        @error('product_name')
                            <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description">Opis produktu</label><br>
                        <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-3 mb-2" type="text"
                            id="description" name="description"
                            value="@if (isset($product)) {{ $product->description }} @else {{old('description')}}  @endif" tabindex="4">
                        @error('description')
                            <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="price">Cena ( € )</label><br>
                        <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-3 mb-2" type="text"
                            id="price" name="price"
                            value="@if (isset($product)) {{ str_replace(',', '', number_format($product->price, 2)) }} @else {{old('price')}} @endif"
                            tabindex="5">
                        @error('price')
                            <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                            {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="price">Kategória</label><br>
                        <select class="w-full h-fit rounded text-dark-purple font-semibold p-2 mt-3 mb-2" type="text"
                            id="price" name="category" tabindex="6">
                            <option value='0' @if (isset($product) && $product->category == 0) selected @endif>Android</option>
                            <option value='1' @if (isset($product) && $product->category == 1) selected @endif>Apple</option>
                            <option value='2' @if (isset($product) && $product->category == 2) selected @endif>News</option>
                        </select>
                    </div>
                </div>
                <div class="h-fit">
                    <div class="flex mb-2 gap-x-4 h-6">
                        <label for="paramter">Parametre</label>
                        <button id="add-parameter" type="button"
                            class="px-2 rounded-full w-fit h-fit font-bold bg-white text-dark-purple hover:text-white hover:bg-dark-purple hover:border-2 hover:border-white">+</button>
                    </div>
                    <div id="parametersHolder"
                        class="grid grid-cols-[38%_38%_12%] justify-center items-center gap-x-4 max-lg:col-start-1 max-lg:row-start-auto w-full max-h-[19vh] overflow-auto">
                        @if (isset($parameters))
                            @foreach ($parameters as $index => $parameter_array)
                                <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2"
                                    type="text" id="parameter-key-{{ $index }}"
                                    name="parameter-key-{{ $index }}"
                                    value="{{ ucfirst($parameter_array->parameter) }}" tabindex="7">
                                <input class="w-full h-8 rounded text-dark-purple font-semibold p-2 mt-1 mb-2"
                                    type="text" id="parameter-value-{{ $index }}"
                                    name="parameter-value-{{ $index }}"
                                    value="{{ ucfirst($parameter_array->value) }}" tabindex="7">
                                <div id="delete-div-{{ $index }}"
                                    class="flex w-fit justify-center items-center">
                                    <button id="delete-parameter-{{ $index }}" type="button"
                                        class="flex justify-center items-center min-w-4 max-h-4 rounded-full  font-bold bg-white text-dark-purple hover:text-white hover:bg-dark-purple hover:border-2 hover:border-white">-</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div id="photosHolder"
                        class="bg-white max-h-none max-lg: mt-2 rounded-lg row-span-3 row-start-3 col-start-2 max-lg:col-start-1 max-lg:row-start-auto">
                        <input id="fileInput" form="updateProducts" name="fileInput[]"
                            class="hidden opacity-100 w-full h-full cursor-pointer" type="file"
                            accept="image/jpeg, image/png" multiple>
                        <label for="fileInput"
                            class=" flex py-10 justify-center items-center flex-col max-lg:flex-row max-lg:gap-2 w-full h-full text-white font-bold rounded cursor-pointer">
                            <img src="{{ asset('img/svg/picture_add.svg') }}" class="max-h-fit">
                            <p class="text-center text-dark-purple font-extrabold text-xl">Pridať obrázok/y</p>
                        </label>

                    </div>
                    @if($errors->has('img'))
                    <p class="animate-custom_pulse animate-once font-light text-red-600 px-2 mb-2 rounded-xl">
                        Produkt musí obsahovať obrázok!</p>
                    @endif
                    <div id="imagePreview"
                        class="grid grid-cols-4 col-start-2 max-lg:col-start-1 rounded-lg pt-4 gap-x-2 max-">
                        @if (isset($images))
                            @foreach ($images as $index => $image)
                                <img id='image_id_img-{{ $index }}'
                                    src="{{ asset('storage/' . $image->link) }}"
                                    class="max-h-fit rounded-lg scale-100 product_image cursor-pointer">
                                <input id="image_id_input-{{ $index }}" hidden
                                    name="image_id-{{ $index }}" form="updateProducts" type="text"
                                    value="{{ $image->link }}">
                            @endforeach
                        @endif
                       
                    </div>
                    <div class=" flex gap-4 col-span-2 max-lg:col-span-1 mt-8 max-lg:mx-auto">
                        <button type="submit" form="updateProducts"
                            class=" border-2 border-green-500 text-green-500 px-4 py-1 w-32 rounded-full hover:font-bold hover:bg-green-500 hover:text-light-green text-center">
                            @if (isset($product))
                                Upraviť
                            @else
                                Vytvoriť
                            @endif
                        </button>
                        <a href="/admin"
                            class=" border-2 border-red-500 text-red-500 px-4 py-1 w-32 rounded-full hover:font-bold hover:bg-red-500 hover:text-light-green text-center">Späť</a>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>


<script src="{{ asset('js/display_img.js') }}"></script>

</html>
