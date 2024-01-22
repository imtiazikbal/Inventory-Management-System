<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->string('qty');
            $table->string('sale_price');
            
            $table->foreignId('invoice_id')->references('id')->on('invoices')->restrictOnDelete()->cascadeOnDeleteonUpdate();
            $table->foreignId('product_id')->references('id')->on('products')->restrictOnDelete()->cascadeOnDeleteonUpdate();
            $table->foreignId('user_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnDeleteonUpdate();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_products');
    }
};
