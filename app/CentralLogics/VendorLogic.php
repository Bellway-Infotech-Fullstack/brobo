<?php

namespace App\CentralLogics;

use App\Models\Vendor;
use App\Models\OrderTransaction;
use App\Models\Review;

class VendorLogic
{
    public static function get_restaurants($limit = 10, $offset = 1, $zone_id, $filter, $type)
    {
        $paginator = Vendor::where('zone_id', $zone_id)
        // ->when($filter=='delivery', function($q){
        //     return $q->delivery();
        // })
        // ->when($filter=='take_away', function($q){
        //     return $q->takeaway();
        // })
        ->Active()
        // ->type($type)
        // ->orderBy('open', 'desc')
        ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'vendors' => $paginator->items()
        ];
    }

    public static function get_latest_restaurants($limit = 10, $offset = 1, $zone_id, $type)
    {
        $paginator = Vendor::where('zone_id', $zone_id)
        ->active()
        ->latest()
        ->limit(50)
        ->get();
        // ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->count(),
            'limit' => $limit,
            'offset' => $offset,
            'vendors' => $paginator
        ];
    }

    public static function get_popular_restaurants($limit = 10, $offset = 1, $zone_id, $type)
    {
        $paginator = Vendor::where('zone_id', $zone_id)
        ->active()
        ->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->limit(50)
        ->get();
        // ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->count(),
            'limit' => $limit,
            'offset' => $offset,
            'vendors' => $paginator
        ];
    }

    public static function get_restaurant_details($vendor_id)
    {
        return Vendor::active()->whereId($vendor_id)->first();
    }

    public static function calculate_restaurant_rating($ratings)
    {
        $ratings = json_decode($ratings, true);
        $total_submit = $ratings[5]+$ratings[4]+$ratings[3]+$ratings[2]+$ratings[1];
        $rating = ($ratings[5]*5+$ratings[4]*4+$ratings[3]*3+$ratings[2]*2+$ratings[1])/($total_submit?$total_submit:1);
        return ['rating'=> $rating, 'total'=> $total_submit];
    }

    public static function update_restaurant_rating($ratings, $product_rating)
    {
        $restaurant_ratings = [1=>0 , 2=>0, 3=>0, 4=>0, 5=>0];

        if($ratings)
        {
            $restaurant_ratings[1] = $ratings[1];
            $restaurant_ratings[2] = $ratings[2];
            $restaurant_ratings[3] = $ratings[3];
            $restaurant_ratings[4] = $ratings[4];
            $restaurant_ratings[5] = $ratings[5];
            $restaurant_ratings[$product_rating] = $ratings[$product_rating] + 1; 
        }
        else
        {
            $restaurant_ratings[$product_rating] = 1;
        }
        return json_encode($restaurant_ratings);
    }

    public static function search_restaurants($name, $zone_id, $category_id= null,$limit = 10, $offset = 1, $type)
    {
        $key = explode(' ', $name);
        $paginator = Vendor::where('zone_id', $zone_id)->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%");
                $q->orWhere('l_name', 'like', "%{$value}%");
            }
        })
        ->when($category_id, function($query)use($category_id){
            $query->whereHas('services.category', function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        })
        ->active()
        // ->type($type)
        ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'vendors' => $paginator->items()
        ];
    }

    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }

    public static function get_earning_data($vendor_id)
    {
        $monthly_earning = OrderTransaction::whereMonth('created_at', date('m'))->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');
        $weekly_earning = OrderTransaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');
        $daily_earning = OrderTransaction::whereDate('created_at', now())->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');
        
        return['monthely_earning'=>(float)$monthly_earning, 'weekly_earning'=>(float)$weekly_earning, 'daily_earning'=>(float)$daily_earning];
    }

    public static function format_export_restaurants($vendor)
    {
        $storage = [];
        foreach($vendor as $item)
        {
            $storage[] = [
                'id'=>$item->id,
                'FirstName'=>$item->f_name,
                'LastName'=>$item->l_name,
                'phone'=>$item->phone,
                'email'=>$item->email,
                // 'latitude'=>$item->latitude,
                // 'longitude'=>$item->longitude,
                // 'openingTime'=>$item->opening_time->format('H:i:s'),
                // 'closeingTime'=>$item->closing_time->format('H:i:s'),
                'zone_id'=>$item->zone_id,
            ];
        }

        return $storage;
    }
}
