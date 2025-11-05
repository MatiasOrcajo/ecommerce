<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    public function __construct(private readonly ProductService $productService)
    {

    }

    /**
     * Retorna un numero random
     *
     * @return int
     */
    private function generateRandomNumber()
    {
        $rand = rand(100, 10000);

        if (Product::where('code', $rand)->first()) {
            $this->generateRandomNumber();
        }

        return $rand;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validateProduct($request);

        $this->saveProductData(new Product(), $request);

        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product
        ], 200);
    }


    /**
     * @param Product $product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Product $product, Request $request)
    {
        $this->validateProduct($request);

        $this->saveProductData($product, $request);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product
        ], 200);
    }


    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!'
        ], 200);
    }


    /**
     * @param Request $request
     * @return void
     */
// Método privado para validar los datos
    private function validateProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specs' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
        ]);
    }


    /**
     * @param Product $product
     * @param Request $request
     * @return void
     *
     */
// Método privado para guardar los datos del producto
    private function saveProductData(Product $product, Request $request)
    {
        $product->fill([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'discount' => 0,
            'description' => $request->description,
            'specs' => $request->specs,
            'code' => $this->generateRandomNumber(),
            'brand' => $request->brand,
        ]);

        $product->save();
    }


    /**
     * Obtiene dos imagenes de un producto segun un color
     *
     * todo implementar esta funcion para traer todas las imagenes de un color en la vista de producto
     *
     * @param Product $product
     * @param Request $request
     * @return mixed
     */
    public function searchProductImagesByColor(Product $product, Request $request)
    {
        return $product->variants()->where('color', $request->color)->first()->pictures()->orderBy('order')->take(2)->get();
    }


    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function show($slug)
    {
        $product = Product::where("slug", $slug)->firstOrFail();
        $categories = \App\Models\Category::all();

        $product->visits += 1;
        $product->save();

        return view('product', compact('product', 'categories'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVariants(Product $product)
    {

        return response()->json($this->productService->getVariants($product));
    }







}
