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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->float('price');
            $table->unsignedInteger('discount')->nullable();
            $table->timestamp('discount_until')->nullable();
            $table->unsignedInteger('sales')->default(0);
            $table->unsignedInteger('visits')->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->string('description')->nullable();
            $table->string('sizes_description')->nullable();
            $table->string('model_reference')->nullable();
            $table->string('specs')->nullable();
            $table->string('code')->nullable();
            $table->string('brand')->nullable();
            $table->string('slug');
            $table->timestamps();
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
