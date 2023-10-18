<?php

namespace App\Models;

use App\Scopes\RestaurantScope;
use App\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    protected $casts = [
        'price' => 'float',
        'restaurant_id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    protected static function booted()
    {
        if(auth('vendor')->check())
        {
            static::addGlobalScope(new RestaurantScope);
        } 
        static::addGlobalScope(new ZoneScope);
    }
}
