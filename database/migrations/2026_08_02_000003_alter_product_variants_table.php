<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'color_id')) {
                $table->foreignId('color_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('product_variants', 'size_id')) {
                $table->foreignId('size_id')->nullable()->after('color_id');
            }
            if (! Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('size_id');
            }
        });

        if (Schema::hasColumn('product_variants', 'color')) {
            DB::table('colors')->insertOrIgnore(
                DB::table('product_variants')
                    ->select(DB::raw('color as hex, color_name as name'))
                    ->distinct()
                    ->get()
                    ->map(fn ($row) => (array) $row)
                    ->toArray()
            );

            DB::table('sizes')->insertOrIgnore(
                DB::table('product_variants')
                    ->select(DB::raw('size as name'))
                    ->distinct()
                    ->get()
                    ->map(fn ($row) => (array) $row)
                    ->toArray()
            );

            DB::statement('
                UPDATE product_variants pv
                INNER JOIN colors c ON c.hex = pv.color
                INNER JOIN sizes s ON s.name = pv.size
                SET pv.color_id = c.id, pv.size_id = s.id
            ');

            DB::table('product_variants')->get()->each(function ($variant) {
                DB::table('product_variants')->where('id', $variant->id)->update([
                    'sku' => 'SKU-'.$variant->product_id.'-'.$variant->color_id.'-'.$variant->size_id,
                ]);
            });
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable(false)->change();
            $table->unsignedBigInteger('size_id')->nullable(false)->change();

            if (! $this->hasForeignKey('product_variants', 'product_variants_color_id_foreign')) {
                $table->foreign('color_id')->references('id')->on('colors');
            }
            if (! $this->hasForeignKey('product_variants', 'product_variants_size_id_foreign')) {
                $table->foreign('size_id')->references('id')->on('sizes');
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->unique(['product_id', 'color_id', 'size_id']);
        });

        if (Schema::hasColumn('product_variants', 'size')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropColumn(['size', 'color', 'color_name']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'color_id', 'size_id']);

            if ($this->hasForeignKey('product_variants', 'product_variants_size_id_foreign')) {
                $table->dropForeign('product_variants_size_id_foreign');
            }
            if ($this->hasForeignKey('product_variants', 'product_variants_color_id_foreign')) {
                $table->dropForeign('product_variants_color_id_foreign');
            }

            if (! Schema::hasColumn('product_variants', 'size')) {
                $table->string('size')->nullable()->after('id');
            }
            if (! Schema::hasColumn('product_variants', 'color')) {
                $table->string('color')->nullable()->after('size');
            }
            if (! Schema::hasColumn('product_variants', 'color_name')) {
                $table->string('color_name')->nullable()->after('color');
            }
        });

        DB::statement('
            UPDATE product_variants pv
            INNER JOIN colors c ON c.id = pv.color_id
            INNER JOIN sizes s ON s.id = pv.size_id
            SET pv.color = c.hex, pv.size = s.name, pv.color_name = c.name
        ');

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['color_id', 'size_id', 'sku']);
        });
    }

    private function hasForeignKey(string $table, string $key): bool
    {
        $db = config('database.default');
        if ($db === 'sqlite') {
            return false;
        }

        return DB::selectOne('
            SELECT COUNT(*) as count
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_NAME = ?
            AND TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
        ', [$key, DB::connection()->getDatabaseName(), $table])->count > 0;
    }
};
