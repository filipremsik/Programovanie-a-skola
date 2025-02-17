<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ParameterProduct;

class ParameterProductSeeder extends Seeder
{
    public function run(): void
    {
        $parameter_products = [];

        for ($i = 1; $i <= 50; $i++) {
            for ($j = 1; $j <= 8; $j++) {
                $parameter_products[] = [
                    'parameter_id' => $j,
                    'product_id' => $i
                ];
            }
        };

        $parameter_products[]=
        [
            'parameter_id' => 11,
            'product_id' => 8
        ];

        $parameter_products[]=
        [
            'parameter_id' => 11,
            'product_id' => 1
        ];

        DB::table('parameter_products')->insert($parameter_products);
    }
}