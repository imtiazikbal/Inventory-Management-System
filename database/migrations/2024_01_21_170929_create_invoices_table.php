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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('total');
            $table->string('discount');
            $table->string('vat');
            $table->string('payable');
            $table->foreignId('user_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnDeleteonUpdate();
            $table->foreignId('customer_id')->references('id')->on('customers')->restrictOnDelete()->cascadeOnDeleteonUpdate();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
