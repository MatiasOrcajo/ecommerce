<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponService{


    /**
     * Validates the provided coupon code.
     *
     * This method ensures that the coupon code exists, checks if it is still valid based on the expiration date,
     * and verifies if the coupon quantity is sufficient. If the validation passes, the coupon quantity is decremented
     * and the changes are saved to the database.
     *
     * @param Request $request The HTTP request containing the coupon code to validate.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating successful coupon validation.
     * @throws \Illuminate\Validation\ValidationException If the provided coupon code does not meet validation requirements.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException If the coupon is expired or out of stock.
     */
    public function validateCoupon(Request $request)
    {

        $validatedData = $request->validate([
           'code' => 'required|string|max:255'
        ]);

        $coupon = Coupon::where('code', $validatedData['code'])->first();

        if (!$coupon){

            return response()->json(['error' => "El cupón no existe"], 400);

        }

        if(Carbon::now()->greaterThan(Carbon::parse($coupon->valid_until))){

            return response()->json(['error' => "El cupón no es válido para esta fecha"], 400);

        }

        if($coupon->quantity == 0){

            return response()->json(['error' => "El cupón está agotado"], 400);

        }

        return response()->json([
            'success' => "Cupón validado con éxito",
            'coupon_id' => $coupon->id,
            ], 200);


    }

}
