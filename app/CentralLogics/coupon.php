<?php

namespace App\CentralLogics;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;

class CouponLogic
{
    public static function get_discount($coupon, $order_amount)
    {
        $discount_ammount = 0;
        if($coupon->discount_type=='percent' && $coupon->discount > 0)
        {
            $discount_ammount = $order_amount* ($coupon->discount/100);
        }
        else
        {
            $discount_ammount = $coupon->discount;
        }
        if($coupon->max_discount > 0)
        {
            $discount_ammount = $discount_ammount > $coupon->max_discount?$coupon->max_discount:$discount_ammount;
        }
        return $discount_ammount;
    }

    public static function is_valide($coupon, $user_id, $service_id)
    {

        $start_date = Carbon::parse($coupon->start_date);
        $expire_date = Carbon::parse($coupon->expire_date);

        $today = Carbon::now();
        if($start_date->format('Y-m-d') > $today->format('Y-m-d') || $expire_date->format('Y-m-d') < $today->format('Y-m-d'))
        {
            return 407;  //coupon expire
        }

        if($coupon->coupon_type == 'vendor_wise' && !in_array($service_id, json_decode($coupon->data, true)))
        {  
            return 404;   
        }
        else if($coupon->coupon_type == 'zone_wise')
        {
            if(json_decode($coupon->data, true)){
                $data = Service::whereHas('vendor', function($q) use($coupon) {

                    $q->whereIn('zone_id', json_decode($coupon->data, true));

                })->where('id', $service_id)->first();
                if(!$data)
                {
                    return 404;
                }
            }
            else
            {
                return 404;
            }
        }
        else if($coupon->coupon_type == 'first_order')
        {
            $total = Order::where(['user_id' => $user_id])->count();
            if ($total < $coupon['limit']) {
                return 200;
            }else{
                return 406;//Limite orer
            }
        }
        if ($coupon['limit'] == null) {
            return 200;
        } else {
            $total = Order::where(['user_id' => $user_id, 'coupon_code' => $coupon['code']])->count();
            if ($total < $coupon['limit']) {
                return 200;
            }else{
                return 406;//Limite orer
            }
        }
        return 404; //not found
    }
}
