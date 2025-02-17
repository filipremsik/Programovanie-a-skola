<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\ShoppingSession;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $session_id)
    {
        $all_parameters = $request->all();

        if (isset($all_parameters['product_id'])) {
            if (!CartItem::where('product_id', $all_parameters['product_id'])->where('session_id', $session_id)->exists()) {
                CartItem::create([
                    'product_id' => $all_parameters['product_id'],
                    'session_id' => $session_id,
                    'quantity' => $all_parameters['quantity']
                ]);
            }
        }
    }

    public function numberOfItems()
    {
        $numberOfItems = 0;

        $user_id = auth()->id();

        $user_session = ShoppingSession::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();

        if (!Order::where("shopping_session_id", $user_session->id)->exists()) {

            $carItems = CartItem::where('session_id', $user_session->id)->get();

            foreach ($carItems as $item) {
                $numberOfItems += $item->quantity;
            }

            return $numberOfItems;
        }
    }

    public function updateQuantity(Request $request)
    {
        $all_parameters = $request->all();

        $cart_item_id = $all_parameters['cart_item_id'];

        $cart_item = CartItem::where('id', $cart_item_id)->first();

        $cart_item->update([
            'quantity' => $all_parameters['quantity']
        ]);


        return (response(["Message" => "Quantity updated"], 201));
    }

    public function deleteItem($id)
    {
        CartItem::where('id', $id)->delete();

        return (response(["Message" => "Item deleted"], 201));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}
