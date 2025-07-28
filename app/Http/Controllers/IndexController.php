<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        $products = Product::where('featured', true)->with('variants.pictures')->get();
        $categories = \App\Models\Category::all();

        return view('index', compact('products', 'categories'));
    }


    public function getFeaturedProducts()
    {

        $products = \App\Models\Product::where('featured', true)
            ->with('variants')
            ->get();

        $data = [];

        foreach ($products as $product) {
            $data[$product->id] = [];
            $data[$product->id]["product"] = [
                "name" => $product->name,
                "price" => $product->price,
                "discount" => $product->discount,
                "discount_until" => $product->discount_until,
                "slug" => $product->slug
            ];

            $variants = $product->variants()->whereHas('pictures')->with('pictures')->get();

            foreach ($variants as $variant) {
                $data[$product->id]["colors"]["names"][] = $variant->color_name;
                $data[$product->id]["colors"][] = [$variant->color => [$variant->pictures()->orderBy('order')->pluck("path")->take(2)->toArray()]];
            }
        }

        return json_encode($data);


    }


    public function searchProducts(Request $request)
    {
        $categories = \App\Models\Category::all();
        $q = $request->q;

        return view('search-products', compact('categories', 'q'));
    }


    public function searchProductsAjax(Request $request)
    {

        $products = Product::where('name', 'like', '%' . $request->q . '%')->get();
        $data = [];

        foreach ($products as $product) {
            $data[$product->id] = [];
            $data[$product->id]["product"] = [
                "name" => $product->name,
                "price" => $product->price,
                "discount" => $product->discount,
                "discount_until" => $product->discount_until,
                "slug" => $product->slug
            ];

            $variants = $product->variants()->whereHas('pictures')->with('pictures')->get();

            foreach ($variants as $variant) {
                $data[$product->id]["colors"]["names"][] = $variant->color_name;
                $data[$product->id]["colors"][] = [$variant->color => [$variant->pictures()->orderBy('order')->pluck("path")->take(2)->toArray()]];
            }
        }

        return json_encode($data);

    }

}
