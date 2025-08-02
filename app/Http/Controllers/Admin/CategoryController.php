<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{

    public function __construct(private readonly CategoryService $categoryService,
                                private readonly ProductService $productService)
    {
    }

    public function index()
    {

        return view('admin.categories');
    }


    public function store(Request $request)
    {

        Category::create([
            "name" => $request->name,
            "slug" => Str::slug($request->name)
        ]);

        return back()->with('success', 'Categoria creada correctamente');
    }

    public function showCategory(Request $request)
    {
        $category = Category::where("slug", $request->slug)->with('products')->first();
        $categories = \App\Models\Category::all();

        return view('search-category', compact('category', 'categories'));
    }


    /**
     *
     */
    public function searchProductsByCategory(Request $request)
    {
        $category = Category::where("slug", $request->slug)->with('products')->first();

        return $this->productService->productsData($category->products);

    }



    public function show(Category $category)
    {
        $categories = \App\Models\Category::all();

        return view('admin.category', compact('category', 'categories'));
    }


    public function listProducts(Category $category)
    {

        $products = $category->products;

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



    public function listCategories()
    {
        return $this->categoryService->listCategories();
    }

}
