<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService{

    /**
     * Valida que un cupón no esté vencido y que tenga cantidad disponible.
     *
     * @param string $code
     * @return Coupon
     * @throws \Exception
     */
    public function validateCoupon(string $code)
    {

        $coupon = Coupon::where('code', $code)->first();
        dd(Carbon::now()->gt(Carbon::parse($coupon->valid_until)));
        if(Carbon::now() > Carbon::parse($coupon->valid_until)){
            throw new \Exception("El cupón no es válido para esta fecha");
        }

        if($coupon->quantity == 0){
            throw new \Exception("El cupón está agotado");
        }

        $coupon->quantity--;
        $coupon->save();

        return $coupon;


    }

}
