<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->string('path', 500);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['product_variant_id', 'order']);
        });

        if (Schema::hasTable('pictures')) {
            DB::statement('
                INSERT INTO variant_pictures (product_variant_id, path, `order`, created_at, updated_at)
                SELECT product_variant_id, path, `order`, created_at, updated_at
                FROM pictures
                WHERE product_variant_id IS NOT NULL
            ');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_pictures');
    }
};
