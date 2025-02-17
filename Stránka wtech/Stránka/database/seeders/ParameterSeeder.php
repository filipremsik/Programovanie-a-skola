<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Add this line

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the parameters data
        $parameters = [
            ['parameter' => 'color', 'value' => 'red'],
            ['parameter' => 'color', 'value' => 'blue'],
            ['parameter' => 'color', 'value' => 'green'],
            ['parameter' => 'size', 'value' => 'small'],
            ['parameter' => 'size', 'value' => 'medium'],
            ['parameter' => 'size', 'value' => 'large'],
            ['parameter' => 'material', 'value' => 'cotton'],
            ['parameter' => 'material', 'value' => 'polyester'],
            ['parameter' => 'material', 'value' => 'wool'],
            ['parameter' => 'brand', 'value' => 'iPhone'],
            ['parameter' => 'brand', 'value' => 'Samsung'],
            ['parameter' => 'brand', 'value' => 'Huawei']
            // Add more parameters as needed
        ];

        // Insert the data into the parameters table
        DB::table('parameters')->insert($parameters);
    }
}