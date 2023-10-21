<?php

namespace App\CentralLogics;

use App\Models\Banner;
use App\Models\Service;
use App\Models\Vendor;
use App\CentralLogics\Helpers;

class BannerLogic
{
    public static function get_banners($zone_id)
    {
        $banners = Banner::active()->where('zone_id', $zone_id)->get();
        $data = [];
        foreach($banners as $banner)
        {
            if($banner->type=='vendor_wise')
            {
                $restaurant = Vendor::find($banner->data);
                $data[]=[
                    'id'=>$banner->id,
                    'title'=>$banner->title,
                    'type'=>$banner->type,
                    'image'=>$banner->image,
                    'vendor'=> $restaurant?Helpers::restaurant_data_formatting($restaurant, false):null,
                    'service'=>null
                ];
            }
            if($banner->type=='service_wise')
            {
                $food = Service::find($banner->data);
                $data[]=[
                    'id'=>$banner->id,
                    'title'=>$banner->title,
                    'type'=>$banner->type,
                    'image'=>$banner->image,
                    'vendor'=> null,
                    'service'=> $food?Helpers::product_data_formatting($food, false):null,
                ];
            }
        }
        return $data;
    }
}
