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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('tin');
            $table->string('phone');
            $table->string('product_name');
            $table->string('staff_name');
            $table->string('product_quantity');
            $table->string('product_price');
            $table->string('payment_date');
            $table->string('amount_paid')->default(0);
            $table->string('status')->nullable()->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
