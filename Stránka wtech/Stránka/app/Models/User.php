<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['login', 'email', 'password', 'name', 'surname', 'address', 'phone_number', 'postal_code', 'temporary', 'admin'];

    const UPDATED_AT = null;

    public function user()
    {
        return [
            $this->belongsToMany(Order::class),
            $this->belongsTo(ShoppingSession::class),
        ];
    }
}