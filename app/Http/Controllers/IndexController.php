<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function politics()
    {
        $categories = \App\Models\Category::all();

        return Inertia::render('Politics', ['categories' => $categories]);
    }

    public function faqs()
    {
        $categories = \App\Models\Category::all();

        return Inertia::render('Faqs', ['categories' => $categories]);
    }

    public function index()
    {
        $products = Product::where('featured', true)
            ->orderBy('price')
            ->where('visible', true)
            ->with('variants.pictures')
            ->get();
        $categories = \App\Models\Category::all();

        return Inertia::render('Index', compact('products', 'categories'));
    }

    public function getFeaturedProducts()
    {

        $products = \App\Models\Product::where('featured', true)
            ->orderBy('price', 'DESC')
            ->where('visible', true)
            ->with('variants')
            ->get();

        return $this->productService->productsData($products);

    }

    public function searchProducts(Request $request)
    {
        $categories = \App\Models\Category::all();
        $q = $request->q;

        return Inertia::render('Search', ['categories' => $categories, 'products' => [], 'query' => $q]);
    }

    /**
     * Busqueda de productos segun ciertas condiciones
     *
     * @return false|string
     */
    public function searchProductsAjax(Request $request)
    {

        $products = Product::where('name', 'like', '%'.$request->q.'%')
            ->orderBy('price', 'desc')
            ->where('visible', true)
            ->get();

        if ($request->q == 'Todos los productos') {
            $products = Product::where('visible', true)->orderBy('price')->get();
        }
        if ($request->q == 'SUMMER SALE') {
            $products = Product::where('visible', true)->orderBy('price')->get();
        }

        return $this->productService->productsData($products);

    }

    public function getMainProducts()
    {
        $products = Product::whereIn('id', [8, 13, 15, 16])->orderBy('price', 'desc')->get();

        return $this->productService->productsData($products);
    }
}
