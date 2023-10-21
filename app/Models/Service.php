<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use App\Scopes\VendorScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    protected $casts = [
        'tax' => 'float',
        'price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'set_menu' => 'integer',
        'category_id' => 'integer',
        'reviews_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'veg'=>'integer',
        // 'category_ids' => 'json',
        // 'variations' => 'json',
        // 'add_ons' => 'json',
        // 'attributes' => 'json',
        // 'choice_options' => 'json'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1)->whereHas('vendor', function($query){
            return $query->where('status', 1);
        });
    }

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

    protected static function booted()
    {
        if(auth('vendor')->check())
        {
            static::addGlobalScope(new VendorScope);
        }

        static::addGlobalScope(new ZoneScope);
    }


    public function scopeType($query, $type)
    {
        if($type == 'veg')
        {
            return $query->where('veg', true);
        }
        else if($type == 'non_veg')
        {
            return $query->where('veg', false);
        }

        return $query;
    }
    

}
