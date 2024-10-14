<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PicturesController extends Controller
{
    //

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, Product $product)
    {
        if($request->file('picture')){
            $reqPicture = $request->file('picture')->store('public/images');
            $reqPictureUrl      = Storage::url($reqPicture);
        }

        $picture = new Picture();
        $picture->product_id = $product->id;
        $picture->order = 1;
        $picture->path = $reqPictureUrl;
        $picture->save();

        return response()->json([
            'message' => 'Picture created successfully!',
            'picture' => $picture
        ], 200);

    }
}
