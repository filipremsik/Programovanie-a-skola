<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterProduct extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'parameter_id',
        'product_id'
    ];

    protected $primaryKey = ['parameter_id', 'product_id'];
    public $incrementing = false;

    public function parameterProduct()
    {
        return [
            $this->hasOne(Product::class),
            $this->hasOne(Parameter::class)
        ];
    }
}