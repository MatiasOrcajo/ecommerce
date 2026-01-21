<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class IndexController extends Controller
{


    public function __construct(private readonly ProductService $productService)
    {

    }


    public function politics()
    {
        $categories = \App\Models\Category::all();

        return view('politics', compact('categories'));
    }


    public function faqs()
    {
        $categories = \App\Models\Category::all();

        return view('faqs', compact('categories'));
    }


    public function index()
    {
        $products = Product::where('featured', true)
            ->where('visible', true)
            ->with('variants.pictures')
            ->get();
        $categories = \App\Models\Category::all();

        return view('index', compact('products', 'categories'));
    }


    public function getFeaturedProducts()
    {

        $products = \App\Models\Product::where('featured', true)
            ->where('visible', true)
            ->with('variants')
            ->get();

        return $this->productService->productsData($products);


    }


    public function searchProducts(Request $request)
    {
        $categories = \App\Models\Category::all();
        $q = $request->q;

        return view('search-products', compact('categories', 'q'));
    }


    /**
     * Busqueda de productos segun ciertas condiciones
     * @param Request $request
     * @return false|string
     */
    public function searchProductsAjax(Request $request)
    {

        $products = Product::where('name', 'like', '%' . $request->q . '%')
            ->where('visible', true)
            ->get();

        if($request->q == "Todos los productos") $products = Product::where('visible', true)->get();
        if($request->q == "SUMMER SALE") $products = Product::where('visible', true)->get();

        return $this->productService->productsData($products);

    }


    public function getMainProducts()
    {
        $products = Product::whereIn('id', [8, 13, 15, 16])->get();

        return $this->productService->productsData($products);
    }



}
