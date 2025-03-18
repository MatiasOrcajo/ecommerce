<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Picture;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    //


    /**
     * Handles the storage of multiple images for a product.
     *
     * @param Request $request The HTTP request containing image files.
     * @param Product $product The product to which the images are associated.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects back with a success message on successful image storage.
     */
    public function store(Request $request, Product $product)
    {

        foreach ($request->images as $file) {

            $reqPicture = $file->store('public/images');
            $reqPictureUrl = Storage::url($reqPicture);

            $picture = new Picture();
            $picture->product_id = $product->id;
            $picture->order = 1;
            $picture->path = $reqPictureUrl;
            $picture->save();


        }

        return back()->with('success', 'Imagen creada correctamente');

    }


    public function destroy(Picture $picture)
    {
        Storage::delete($picture->path);
        $picture->delete();

    }


    public function getPictures (Product $product)
    {
        $pictures = $product->pictures;

        return response()->json($pictures);
    }


    /**
     * Updates the order of pictures associated with a product.
     *
     * @param Product $product The product associated with the pictures.
     * @param Request $request The HTTP request containing the updated order data.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects back with a success message on successful order update.
     */
    public function editOrder(Product $product, Request $request)
    {

        $datos = collect($request->all())->except(['_token', '_method']);

        foreach ($datos as $key => $value) {
            $picture = Picture::find($key);
            $picture->order = $value;
            $picture->save();
        }

        return back()->with('success', 'Orden editado correctamente');

    }

}
