<?php

namespace Tests\Unit;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductService;

        Size::insert([
            ['name' => 'S', 'sort_order' => 2],
            ['name' => 'M', 'sort_order' => 3],
            ['name' => 'L', 'sort_order' => 4],
        ]);

        Color::insert([
            ['hex' => '#000000', 'name' => 'Negro'],
            ['hex' => '#FFFFFF', 'name' => 'Blanco'],
        ]);
    }

    public function test_calculate_product_price_with_discount(): void
    {
        $product = new Product(['price' => 1000, 'discount' => 20]);

        $result = $this->service->calculateProductPriceWithDiscount($product);

        $this->assertSame(200.0, $result);
    }

    public function test_get_variants_returns_correct_structure(): void
    {
        $product = Product::factory()->create();
        $colorBlack = Color::where('hex', '#000000')->first();
        $colorWhite = Color::where('hex', '#FFFFFF')->first();
        $sizeS = Size::where('name', 'S')->first();
        $sizeM = Size::where('name', 'M')->first();

        $variant1 = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => $sizeS->id,
            'stock' => 10,
        ]);

        $variant2 = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => $sizeM->id,
            'stock' => 5,
        ]);

        $variant3 = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorWhite->id,
            'size_id' => $sizeS->id,
            'stock' => 3,
        ]);

        $result = $this->service->getVariants($product);

        $this->assertArrayHasKey('availableColors', $result);
        $this->assertArrayHasKey('availableSizes', $result);
        $this->assertArrayHasKey('productsVariantsArray', $result);
        $this->assertArrayHasKey('youtube_link', $result);

        $this->assertCount(2, $result['availableColors']);
        $this->assertCount(2, $result['availableSizes']);
        $this->assertCount(3, $result['productsVariantsArray']);
    }

    public function test_get_variants_groups_colors_correctly(): void
    {
        $product = Product::factory()->create();
        $colorBlack = Color::where('hex', '#000000')->first();
        $sizeS = Size::where('name', 'S')->first();
        $sizeM = Size::where('name', 'M')->first();

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => $sizeS->id,
            'stock' => 10,
        ]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => $sizeM->id,
            'stock' => 5,
        ]);

        $result = $this->service->getVariants($product);

        $this->assertCount(1, $result['availableColors']);
        $this->assertSame('Negro', $result['availableColors'][0]['color_name']);
        $this->assertSame('#000000', $result['availableColors'][0]['color']);
    }

    public function test_get_variants_prioritizes_color_with_pictures(): void
    {
        $product = Product::factory()->create();
        $colorBlack = Color::where('hex', '#000000')->first();
        $colorWhite = Color::where('hex', '#FFFFFF')->first();
        $sizeS = Size::where('name', 'S')->first();

        $variantNoPic = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => $sizeS->id,
            'stock' => 10,
        ]);

        $variantWithPic = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => Size::where('name', 'M')->first()->id,
            'stock' => 5,
        ]);

        $variantWithPic->pictures()->create([
            'path' => '/images/test.jpg',
            'order' => 1,
        ]);

        $result = $this->service->getVariants($product);

        $blackColor = collect($result['availableColors'])->firstWhere('color_name', 'Negro');
        $this->assertNotEmpty($blackColor['pics']['paths']);
        $this->assertSame('/images/test.jpg', $blackColor['pics']['paths'][0]);
    }

    public function test_products_data_returns_correct_structure(): void
    {
        $product = Product::factory()->create(['name' => 'Test Product', 'price' => 5000]);
        $colorBlack = Color::where('hex', '#000000')->first();
        $sizeS = Size::where('name', 'S')->first();

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => $sizeS->id,
            'stock' => 10,
        ]);

        $variant->pictures()->create([
            'path' => '/images/p1.jpg',
            'order' => 1,
        ]);

        $variant->pictures()->create([
            'path' => '/images/p2.jpg',
            'order' => 2,
        ]);

        $products = Product::where('id', $product->id)->get();

        $result = $this->service->productsData($products);

        $this->assertIsArray($result);
        $this->assertArrayHasKey($product->id, $result);
        $this->assertArrayHasKey('product', $result[$product->id]);
        $this->assertArrayHasKey('colors', $result[$product->id]);
        $this->assertSame('Test Product', $result[$product->id]['product']['name']);
        $this->assertCount(1, $result[$product->id]['colors']);
        $this->assertSame('Negro', $result[$product->id]['colors'][0]['name']);
        $this->assertSame('#000000', $result[$product->id]['colors'][0]['hex']);
        $this->assertCount(2, $result[$product->id]['colors'][0]['paths']);
    }

    public function test_products_data_excludes_variants_without_pictures(): void
    {
        $product = Product::factory()->create();
        $colorBlack = Color::where('hex', '#000000')->first();
        $colorWhite = Color::where('hex', '#FFFFFF')->first();
        $sizeS = Size::where('name', 'S')->first();

        $variantWithPic = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorBlack->id,
            'size_id' => $sizeS->id,
        ]);
        $variantWithPic->pictures()->create(['path' => '/img.jpg', 'order' => 1]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $colorWhite->id,
            'size_id' => $sizeS->id,
        ]);

        $products = Product::where('id', $product->id)->get();
        $result = $this->service->productsData($products);

        $this->assertCount(1, $result[$product->id]['colors']);
        $this->assertSame('Negro', $result[$product->id]['colors'][0]['name']);
    }
}
