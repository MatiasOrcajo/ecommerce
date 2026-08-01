<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function showCategory(Request $request)
    {
        $category = Category::where('slug', $request->slug)->with('products')->first();
        $categories = \App\Models\Category::all();

        return Inertia::render('Category', ['category' => $category, 'categories' => $categories]);
    }

    public function searchProductsByCategory(Request $request)
    {
        $category = Category::where('slug', $request->slug)->with('products')->first();

        return $this->productService->productsData($category->products);
    }
}
