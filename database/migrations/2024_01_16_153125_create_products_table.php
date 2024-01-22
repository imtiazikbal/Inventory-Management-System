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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('categories')->restrictOnDelete()->cascadeOnDeleteonUpdate();
            $table->foreignId('user_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnDeleteonUpdate();
            $table->string('name',150);
            $table->string('price',50);
            $table->string('unit',50);
            $table->string('img_url');
           
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
