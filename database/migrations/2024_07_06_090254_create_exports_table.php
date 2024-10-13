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
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            // $table->string('product_id');
            $table->string('tin')->nullable();
            $table->string('product_name');
            $table->string('customer_name')->nullable();
            $table->string('staff_name');
            $table->string('product_quantity');
            $table->string('product_price');
            $table->string('phone')->nullable();
            $table->string('sale_mode')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('status')->nullable();
            // $table->string('product_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
