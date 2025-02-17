<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

use App\Models\ShoppingSession;
use App\Http\Requests\StoreShoppingSessionRequest;
use App\Http\Requests\UpdateShoppingSessionRequest;
use Illuminate\Http\Request;

class ShoppingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function cart()
    {
        return view('shopping-cart');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $all_parameters = $request->all();

        $new_session = ShoppingSession::create([
            'user_id' => auth()->id(),
            'total' => $all_parameters['price'] ?? 0,
        ]);

        $session_id = $new_session->id;

        $cart_item = new CartItemController();

        $cart_item->create($request, $session_id);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShoppingSessionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShoppingSession $shoppingSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShoppingSession $shoppingSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShoppingSessionRequest $request, ShoppingSession $shoppingSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShoppingSession $shoppingSession)
    {
        //
    }
}