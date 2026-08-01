<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('admin.products', compact('categories'));
    }

    /**
     * Fetches a list of products along with their associated categories and pictures,
     * processes the product data into a specific structure, and returns it formatted
     * for use with DataTables.
     *
     * @return JsonResponse
     */
    public function listProducts()
    {

        $products = Product::with('category', 'pictures')->get();

        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category' => $product->category->name,
                'sales' => $product->sales,
                'stock' => $product->stock,
                'price' => $product->price,
                'visits' => $product->visits,
                'featured' => $product->featured,
                'picture' => $product->pictures->first()->path ?? null,
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function update(Request $request, Product $product)
    {
        $date = Carbon::parse($request->discount_until);
        $request['discount_until'] = $date->format('Y-m-d');
        $product->update($request->toArray());
        $product->slug = Str::slug($product->name);
        $product->save();

        return back()->with('success', 'Producto editado correctamente');

    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        Product::create($data);

        return back()->with('success', 'Producto creado correctamente');
    }

    public function show(Product $product)
    {
        $categories = Category::all();
        $productVariants = $product->variants();

        $productColors = [
            'ids_product_variants' => array_unique($productVariants->pluck('id')->toArray()),
            'colors_names' => array_unique($productVariants->pluck('color_name')->toArray()),
        ];

        $picturesByProductVariants = $productVariants->whereHas('pictures')
            ->with('pictures')
            ->get();

        return view('admin.product', compact('product', 'categories', 'productVariants', 'productColors', 'picturesByProductVariants'));
    }

    public function createSize(Product $product, Request $request)
    {
        $productVariant = new ProductVariant;
        $productVariant->size = $request->size;
        $productVariant->stock = $request->stock;
        $productVariant->color = $request->color;
        $productVariant->color_name = $request->color_name;
        $productVariant->product_id = $product->id;
        $productVariant->save();

        return back()->with('success', 'Talle añadido correctamente');

    }

    public function listSizes(Product $product)
    {
        return DataTables::of($product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'size' => $variant->size,
                'color' => $variant->color,
                'color_name' => $variant->color_name,
                'stock' => $variant->stock,
            ];
        }))->make(true);
    }

    public function updateSizeStock(ProductVariant $productVariant, Request $request)
    {
        $productVariant->stock = $request->stock;
        $productVariant->save();
    }

    public function updateDescriptionSizesAndReferences(Request $request, Product $product)
    {
        $product->update($request->toArray());

        return back()->with('success', 'Descripciones editadas correctamente');
    }
}
