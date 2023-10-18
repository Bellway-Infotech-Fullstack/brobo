<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Restaurant;
use Carbon\Carbon;

class Vendor extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'active' => 'boolean'
    ];

    protected $hidden = [
        'password', 'auth_token', 'remember_token',
        'aadhaar_card_number', 'ifsc_code', 'pan_card_image',
        'aadhaar_back_image', 'aadhaar_front_image', 'pan_card_number'
    ];

    public function names(){
        return $this->f_name . ' ' . $this->l_name;
    }

    public function rating(){
        return json_decode($this->rating, true);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function order_transaction()
    {
        return $this->hasMany(OrderTransaction::class);
    }
    
    public function todays_earning()
    {
        return $this->hasMany(OrderTransaction::class)->whereDate('created_at',now());
    }

    public function this_week_earning()
    {
        return $this->hasMany(OrderTransaction::class)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function this_month_earning()
    {
        return $this->hasMany(OrderTransaction::class)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
    }

    public function todaysorders()
    {
        return $this->hasManyThrough(Order::class, Vendor::class)->whereDate('orders.created_at',now());
    }

    public function this_week_orders()
    {
        return $this->hasManyThrough(Order::class, Vendor::class)->whereBetween('orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function this_month_orders()
    {
        return $this->hasManyThrough(Order::class, Vendor::class)->whereMonth('orders.created_at', date('m'))->whereYear('orders.created_at', date('Y'));
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function scopeWeekday($query)
    {
        return $query->where('off_day', 'not like', "%".now()->dayOfWeek."%");
    }

    public function withdrawrequests()
    {
        return $this->hasMany(WithdrawRequest::class);
    }
    public function wallet()
    {
        return $this->hasOne(RestaurantWallet::class, 'vendor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Service::class);
    }

}
