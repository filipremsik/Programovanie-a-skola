<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total'];

    const UPDATED_AT = null;

    public function shoppingSession()
    {
        return [
            $this->belongsToMany(CartItem::class),
            $this->belongsTo(Order::class),
            $this->hasOne(User::class)
        ];
    }

    public function updateTotal()
    {
        $user_id = auth()->id();

        $user_session = ShoppingSession::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();


        $cart_items = CartItem::where('session_id', $user_session->id)->get();

        $total = 0;

        foreach ($cart_items as $item) {
            $product = Product::where('id', $item->product_id)->first();
            $total += $product->price * $item->quantity;
        }

        $user_session->update([
            'total' => $total
        ]);
    }
}
