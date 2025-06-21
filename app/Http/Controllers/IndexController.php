<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        $products = Product::where('featured', true)->with('pictures')->get();

        return view('index', compact('products'));
    }


    public function show($slug)
    {
        $product = Product::where("slug", $slug)->firstOrFail();

        return view('product', compact('product'));
    }

}
