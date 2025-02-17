<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    
    protected $fillable = [
        'user_id',
        'shopping_session_id',
        'payment_method',
        'delivery_method',
        'status',
        'created_at'
    ];

    public function order()
    {
        return [
            $this->hasOne(User::class),
            $this->hasOne(ShoppingSession::class)
        ];
    }
}