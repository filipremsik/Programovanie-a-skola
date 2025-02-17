<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category'
    ];

    public function product()
    {
        return [
            $this->belongsTo(CartItem::class),
            $this->belongsToMany(ParameterProduct::class),
            $this->belongsToMany(Image::class)
        ];
    }
}
