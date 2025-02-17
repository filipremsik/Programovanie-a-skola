<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

use App\Http\Controllers\v1\ShoppingSessionController;
use App\Models\CartItem;
use App\Models\ShoppingSession;
use App\Models\Product;
use App\Models\Order;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_users = User::all();

        return view('shop', [
            'array' => $all_users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function registration(Request $request)
    {
        #TODO V situácií, kde user sa chce zaregistrovať a je zatiaľ temporary iba updatnúť jeho dáta
        $validationOfData = $request->validate(
            [            
                'login' => 'required|max:64|unique:users,login',
                'email' => 'required|email|max:254|unique:users,email',
                'password' => 'required|min:8|regex:/[!@#$%^&*()\-+=\[\]{};:",.<>\/?]/',
                'address' => 'required | max:255',
                'postal_code' => 'required | nullable|digits:5',
                'phone' => 'required | max:20 | unique:users,phone_number',
                'first_name' => 'required | max:35',
                'last_name' => 'required | max:35'
            ],
            [
                'login.required' => 'Prihlásenie je povinné',
                'email.required' => 'E-mail je povinný',
                'password.required' => 'Heslo je povinné',
                'password.regex' => 'Heslo musí obsahovať aspoň jeden špeciálny znak',
                'password.min' => 'Heslo musí mať aspoň 8 znakov',
                'phone.unique' => 'Toto telefónne číslo už je registrované',
                'login.unique' => 'Užívateľ s týmto prihlasovacím menom existuje',
                'email.unique' => 'Užívateľ s týmto e-mailom existuje',
                'first_name' => 'Meno je povinné',
                'last_name' => 'Priezvisko je povinné',
                'address' => 'Adresa je povinná',
                'postal_code' => 'PSČ je povinné',
                'phone' => 'Telefónne číslo je povinné',
            ]
        );

        try {
            $user = User::create([
                'login' => $validationOfData['login'],
                'email' => $validationOfData['email'],
                'password' => Hash::make($validationOfData['password']),
                'address' => $validationOfData['address'],
                'postal_code' => $validationOfData['postal_code'],
                'phone_number' => $validationOfData['phone'],
                'name' => $validationOfData['first_name'],
                'surname' => $validationOfData['last_name'],
                'temporary' => false
            ]);
        } catch (Exception $e) {
            $code_value = $e->getCode();
            $code_value = ['code' => $code_value];
            return redirect()->back()->withErrors($code_value)->withInput();
        };

        return view('login', [
            'message' => 'Registrácia prebehla úspešne'
        ]);
    }

    public function login(Request $request)
    {
        $validationOfData = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ], [
            'login.required' => 'Prihlasovacie meno je povinné',
            'password.required' => 'Heslo je povinné'
        ]);

        $credentials = $request->only('login', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return Redirect::to('/');
        } else {
            return view('login', [
                'data' => $validationOfData
            ]);
        }
    }

    public function logout(Request $request)
    {
        $this->deleteTemporary();

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function temporaryAccount(Request $request)
    {
        $this->deleteTemporary();
        $authorized = auth()->check();

        if (!$authorized) {
            $user = User::create([
                'login' => null,
                'email' => null,
                'password' => null,
                'temporary' => true
            ]);


            Auth::login($user);

            $shopping_session = new ShoppingSessionController();
            $shopping_session->create($request);
        }

        $my_session = ShoppingSession::where('user_id', auth()->id())->orderBy('created_at', 'desc')->first();

        if (((!isset($my_session)) || Order::where("shopping_session_id", $my_session->id)->exists()) && auth()->user()->temporary == false) {
            $shopping_session = new ShoppingSessionController();
            $shopping_session->create($request);
            $my_session = ShoppingSession::where('user_id', auth()->id())->orderBy('created_at', 'desc')->first();
        }

        if ($authorized && $request->has('product_id')) {
            $cart_item = new CartItemController();
            $cart_item->create($request, $my_session->id);
        }

        if (isset($my_session)) {
            $all_cart_items = CartItem::where('session_id', $my_session->id)->get();
        }

        $cart_ids = [];
        $all_product_names = [];
        $all_product_prices = [];
        $all_product_quantities = [];
        $total_price = 0;

        if (isset($all_cart_items)) {
            foreach ($all_cart_items as $cart_item) {
                $product_id = $cart_item->product_id;
                $product = Product::where('id', $product_id)->first();
                array_push($all_product_names, $product->name);
                array_push($all_product_prices, $product->price);
                array_push($all_product_quantities, $cart_item->quantity);
                array_push($cart_ids, $cart_item->id);

                $total_price += $product->price * $cart_item->quantity;
            }
        }

        $total_price_taxed = $total_price * 1.2;

        return view('shopping-cart', [
            'product_names' => $all_product_names ?? null,
            'product_prices' => $all_product_prices ?? null,
            'product_quantities' => $all_product_quantities ?? null,
            'total_price' => $total_price ?? null,
            'cart_items' => $cart_ids ?? null,
            'total_price_taxed' => $total_price_taxed ?? null
        ]);
    }

    public function deleteTemporary()
    {
        $user = User::where('temporary', true, 'created_at',)->get();

        $now = Carbon::now();

        foreach ($user as $u) {
            if ($now->diffInMinutes($u->created_at) > 31) {
                $u->delete();
            }
        }
    }

    public function profile()
    {
        $user_id = Auth::id();

        $orders = Order::where('user_id', $user_id)->get();
        $shopping_sessions = ShoppingSession::where('user_id', $user_id)->get();

        $all_cart_items = [];
        $all_products = [];


        foreach ($shopping_sessions as $session) {
            $cart_items = CartItem::where('session_id', $session->id)->get();
            array_push($all_cart_items, $cart_items);

            foreach ($cart_items as $cart_item) {
                $product = Product::where('id', $cart_item->product_id)->first();
                array_push($all_products, $product);
            }
        }

        return view('profile', [
            'orders' => $orders,
            'shopping_sessions' => $shopping_sessions,
            'cart_items' => $all_cart_items,
            'products' => $all_products
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
