<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Quitar la FK antigua y renombrar la columna
        Schema::table('cart_products', function (Blueprint $table) {
            // Si existía FK a products(product_id)
            try {
                $table->dropForeign(['product_id']);
            } catch (\Throwable $e) {
                // ignora si no existe
            }

            // Renombrar la columna
            $table->renameColumn('product_id', 'product_variants_id');
        });

        // 2) Asegurar tipo y crear la nueva FK a product_variants(id)
        Schema::table('cart_products', function (Blueprint $table) {
            // Ajusta el tipo si es necesario (normalmente unsignedBigInteger)
            $table->unsignedBigInteger('product_variants_id')->change();

            $table->foreign('product_variants_id')
                ->references('id')
                ->on('product_variants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cart_products', function (Blueprint $table) {
            try {
                $table->dropForeign(['product_variants_id']);
            } catch (\Throwable $e) {
                // ignora si no existe
            }

            $table->renameColumn('product_variants_id', 'product_id');
        });

        Schema::table('cart_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }
};
