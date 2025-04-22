<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{


    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    public function index()
    {
        return view('admin.categories');
    }


    public function store(Request $request)
    {
        Category::create($request->toArray());

        return back()->with('success', 'Categoria creada correctamente');
    }

    public function show(Category $category)
    {
        return view('admin.category', compact('category'));
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
