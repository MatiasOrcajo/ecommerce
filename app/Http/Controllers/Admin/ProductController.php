<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Picture;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        Product::create($request->toArray());

        return back()->with('success', 'Producto creado correctamente');
    }


    public function show(Product $product)
    {
        $categories = Category::all();
        $product->load('pictures');

        return view('admin.product', compact('product', 'categories'));
    }



}
