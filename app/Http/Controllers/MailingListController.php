<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailMarketingDiscountEmail;
use App\Models\Coupon;
use App\Models\MailingList;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MailingListController extends Controller
{


    public function store(Request $request)
    {

        if(MailingList::where('email', $request->email)->first()){

            throw new \Error('El email ya existe en nuestra base de datos.');
        }

        $customer = MailingList::create($request->toArray());
        $coupon = new Coupon();
        $coupon->code = $this->generateCode();
        $coupon->discount = 10;
        $coupon->quantity = 1;
        $coupon->valid_until = Carbon::now()->addDays(365);
        $coupon->save();

        SendEmailMarketingDiscountEmail::dispatch($customer, $coupon)->delay(now()->addSeconds(5));



        return true;
    }


    private function generateCode ()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '10-';

        for ($i = 0; $i < 9; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        if(Order::where("code", $code)->first()){
            $this->generateCode();
        }

        return $code;
    }


}
