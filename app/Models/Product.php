<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use App\Scopes\VendorScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    protected $casts = [
        'tax' => 'float',
        'price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'category_id' => 'integer',
        'reviews_count' => 'integer',
        'created_at' => 'datetime',
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
        $category=Category::find(json_decode($this->category_ids)[0]->id);
        return $category?$category->name:trans('messages.uncategorize');
    }




    

}
