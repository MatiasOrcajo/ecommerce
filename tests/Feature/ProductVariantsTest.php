<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantsTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    private Color $colorBlack;

    private Color $colorWhite;

    private Size $sizeS;

    private Size $sizeM;

    private Size $sizeL;

    protected function setUp(): void
    {
        parent::setUp();

        Size::insert([
            ['name' => 'S', 'sort_order' => 2],
            ['name' => 'M', 'sort_order' => 3],
            ['name' => 'L', 'sort_order' => 4],
        ]);

        Color::insert([
            ['hex' => '#000000', 'name' => 'Negro'],
            ['hex' => '#FFFFFF', 'name' => 'Blanco'],
        ]);

        $this->colorBlack = Color::where('hex', '#000000')->first();
        $this->colorWhite = Color::where('hex', '#FFFFFF')->first();
        $this->sizeS = Size::where('name', 'S')->first();
        $this->sizeM = Size::where('name', 'M')->first();
        $this->sizeL = Size::where('name', 'L')->first();

        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 5000,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'color_id' => $this->colorBlack->id,
            'size_id' => $this->sizeS->id,
            'sku' => 'SKU-TEST-BLACK-S',
            'stock' => 10,
        ]);

        $variant->pictures()->create(['path' => '/images/black-s.jpg', 'order' => 1]);

        ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'color_id' => $this->colorWhite->id,
            'size_id' => $this->sizeM->id,
            'sku' => 'SKU-TEST-WHITE-M',
            'stock' => 5,
        ]);
    }

    private function createVariant(Color $color, Size $size, int $stock = 5): ProductVariant
    {
        $product = Product::factory()->create();

        return ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'sku' => 'SKU-'.strtoupper(uniqid()),
            'stock' => $stock,
        ]);
    }

    public function test_get_variants_endpoint_returns_json(): void
    {
        $response = $this->getJson("/products/{$this->product->id}/get-variants");

        $response->assertOk()
            ->assertJsonStructure([
                'availableColors',
                'availableSizes',
                'productsVariantsArray',
                'youtube_link',
            ]);
    }

    public function test_get_variants_endpoint_returns_correct_color_count(): void
    {
        $response = $this->getJson("/products/{$this->product->id}/get-variants");

        $response->assertJsonCount(2, 'availableColors');
        $response->assertJsonCount(2, 'availableSizes');
    }

    public function test_get_variants_endpoint_returns_color_with_correct_fields(): void
    {
        $response = $this->getJson("/products/{$this->product->id}/get-variants");

        $response->assertJsonPath('availableColors.0.color', '#000000');
        $response->assertJsonPath('availableColors.0.color_name', 'Negro');
        $response->assertJsonPath('availableColors.0.pics.paths.0', '/images/black-s.jpg');
    }

    public function test_product_variant_relationships(): void
    {
        $variant = $this->product->variants()->first();

        $this->assertInstanceOf(Product::class, $variant->product);
        $this->assertInstanceOf(Color::class, $variant->color);
        $this->assertInstanceOf(Size::class, $variant->size);
        $this->assertSame('#000000', $variant->color->hex);
        $this->assertSame('Negro', $variant->color->name);
        $this->assertSame('S', $variant->size->name);
        $this->assertSame(10, $variant->stock);
    }

    public function test_product_variant_find_first_similar_variant_with_picture(): void
    {
        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $this->colorWhite->id,
            'size_id' => $this->sizeM->id,
            'sku' => 'SKU-SEARCH-1',
            'stock' => 5,
        ]);

        $variant2 = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $this->colorWhite->id,
            'size_id' => $this->sizeL->id,
            'sku' => 'SKU-SEARCH-2',
            'stock' => 5,
        ]);
        $variant2->pictures()->create(['path' => '/images/fallback.jpg', 'order' => 1]);

        $result = $variant->findFirstSimilarVariantWithPicture();

        $this->assertSame('/images/fallback.jpg', $result);
    }

    public function test_product_variant_find_first_similar_variant_returns_own_picture_first(): void
    {
        $variant = $this->createVariant($this->colorBlack, $this->sizeL);
        $variant->pictures()->create(['path' => '/images/own.jpg', 'order' => 1]);

        $result = $variant->findFirstSimilarVariantWithPicture();

        $this->assertSame('/images/own.jpg', $result);
    }

    public function test_product_variant_find_first_similar_variant_returns_null_when_no_pictures(): void
    {
        $variant = $this->createVariant($this->colorWhite, $this->sizeL);

        $result = $variant->findFirstSimilarVariantWithPicture();

        $this->assertNull($result);
    }

    public function test_product_pictures_relationship(): void
    {
        $product = Product::factory()->create();
        $product->productPictures()->create(['path' => '/images/prod.jpg', 'order' => 1]);

        $this->assertCount(1, $product->productPictures);
        $this->assertCount(1, $product->pictures);
        $this->assertSame('/images/prod.jpg', $product->pictures->first()->path);
    }

    public function test_variant_pictures_relationship(): void
    {
        $variant = $this->product->variants()->first();
        $variant->pictures()->create(['path' => '/images/var.jpg', 'order' => 1]);

        $fresh = ProductVariant::with('pictures')->find($variant->id);
        $this->assertCount(2, $fresh->pictures);
    }

    public function test_color_model_has_variants_relationship(): void
    {
        $variants = $this->colorBlack->variants;

        $this->assertCount(1, $variants);
        $this->assertInstanceOf(ProductVariant::class, $variants->first());
    }

    public function test_size_model_has_variants_relationship(): void
    {
        $variants = $this->sizeS->variants;

        $this->assertCount(1, $variants);
        $this->assertInstanceOf(ProductVariant::class, $variants->first());
    }
}
