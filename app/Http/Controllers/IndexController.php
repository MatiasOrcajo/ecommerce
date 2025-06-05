<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        return view('index');
    }


    public function show($slug)
    {
        $product = Product::where("slug", $slug)->firstOrFail();

        return view('product', compact('product'));
    }

}
