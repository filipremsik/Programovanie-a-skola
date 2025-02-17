<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parameter_products', function (Blueprint $table) {
            $table->unsignedInteger("parameter_id");
            $table->unsignedInteger("product_id");

            $table->foreign("parameter_id")->references("id")->on("parameters");
            $table->foreign("product_id")->references("id")->on("products")->cascadeOnDelete();

            $table->primary(["parameter_id", "product_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_products');
    }
};