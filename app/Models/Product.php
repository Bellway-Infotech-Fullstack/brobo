<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use App\Scopes\VendorScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProductColoredImage;

class Product extends Model
{
    protected $casts = [
        'tax' => 'float',
        'price' => 'string',
        'status' => 'integer',
        'discount' => 'float',
        'category_id' => 'integer',
        'created_at' => 'datetime',
        'images'=>'array',
        'updated_at' => 'datetime',
    ];


    public function scopePopular($query)
    {
        return $query;//->orderBy('order_count', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

     public function discount()
    {
        return $this->hasOne(Discount::class);
    }

    public function rating()
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, count(service_id) rating_count, service_id'))
            ->groupBy('service_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(OrderDetail::class);
    }


    public function getCategoryAttribute()
    {
    if (!empty($this->category_ids)) {
        $decodedCategoryIds = json_decode($this->category_ids);

        // Check if the array is not empty and the first element exists
        if (!empty($decodedCategoryIds) && isset($decodedCategoryIds[0]->id)) {
            $category = Category::find($decodedCategoryIds[0]->id);
            return $category ? $category->name : trans('messages.uncategorize');
        }
    }

    return trans('messages.uncategorize');
  }

    // Product.php (Product model)
    public function coloredImages()
    {
        return $this->hasMany(ProductColoredImage::class,'product_id');
    }





    

}
