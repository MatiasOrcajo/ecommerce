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

        if(Carbon::now()->format('d-m-Y') > Carbon::parse($coupon->valid_until)->format('d-m-Y')){

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
