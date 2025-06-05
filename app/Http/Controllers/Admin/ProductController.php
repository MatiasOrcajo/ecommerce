<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Picture;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products');
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
                "id" => $product->id,
                "name" => $product->name,
                "description" => $product->description,
                "category" => $product->category->name,
                "sales" => $product->sales,
                "stock" => $product->stock,
                "price" => $product->price,
                "visits" => $product->visits,
                "picture" => $product->pictures->first()->path ?? null,
            ];
        });

        return DataTables::of($data)->make(true);
    }


    public function update(Request $request, Product $product)
    {

        $product->update($request->toArray());

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
        $product->load('pictures');

        return view('admin.product', compact('product', 'categories'));
    }



    public function createSize(Product $product, Request $request)
    {
        $productSize = new ProductSize();
        $productSize->size = $request->size;
        $productSize->stock = $request->stock;
        $productSize->product_id = $product->id;
        $productSize->save();

        return back()->with('success', 'Talle añadido correctamente');

    }


    public function listSizes(Product $product)
    {
        return DataTables::of($product->sizes->map(function ($size) {
            return [
                "id" => $size->id,
                "size" => $size->size,
                "stock" => $size->stock,
            ];
        }))->make(true);
    }


    public function updateSizeStock(Product $product, ProductSize $productSize, Request $request)
    {
        $productSize->stock = $request->stock;
        $productSize->save();
    }


}
