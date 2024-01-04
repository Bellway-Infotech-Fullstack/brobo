<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Scopes\ZoneScope;

class Order extends Model
{

    protected $fillable = ['start_date', 'end_date','time_duration', 'user_id','status','cart_items','paid_amount','pending_amount','delivery_address_id','coupon_id','delivery_charge','order_installment_percent','transaction_id','order_id','description','extended_order_transaction_id','final_item_price'];

    protected $casts = [
        'order_id' => 'string',
        'description' => 'string',
        'start_date' => 'string',        
        'end_date' => 'string',
        'time_duration' => 'string',
        'user_id' => 'integer',
        'status' => 'string',
        'cart_items' => 'string',
        'paid_amount' => 'float',
        'pending_amount' => 'float',
        'final_item_price' => 'float',
        'delivery_address_id' => 'integer',
        'coupon_id' => 'integer',
        'delivery_charge'=>'integer',
        'order_installment_percent' => 'integer',
        'transaction_id' => 'string',
        'extended_order_transaction_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function setDeliveryChargeAttribute($value)
    {
        $this->attributes['delivery_charge'] = round($value, 3);
    }

 
  

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }




    public function scopeAccepteByDeliveryman($query)
    {
        return $query->where('status', 'accepted');
    }



    public function scopePreparing($query)
    {
        return $query->whereIn('status', ['processing']);
    }
    
    public function scopeOngoing($query)
    {
        return $query->whereIn('status', ['accepted','confirmed','processing','handover','picked_up']);
    }
    
    public function scopeFoodOnTheWay($query)
    {
        return $query->where('status','picked_up');
    }


    public function scopeServiceOngoing($query)
    {
        return $query->whereIn('status',['ongoing']);
    }


    public function scopeAll($query)
    {
        return $query->whereIn('status',['ongoing', 'failed', 'cancelled', 'delivered', 'completed', 'refunded']);
    }
    
    // public function scopeRefundRequest($query)
    // {
    //     return $query->where('status','refund_requested');
    // }

    public function scopeFailed($query)
    {
        return $query->where('status','failed');
    }
    
    public function scopeCancelled($query)
    {
        return $query->where('status','cancelled');
    }
    
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['delivered', 'completed']);
    }
    
    public function scopeRefunded($query)
    {
        return $query->where('status','refunded');
    }
    
    public function scopeSearchingForDeliveryman($query)
    {
        return $query->whereNull('delivery_man_id')->where('order_type', '=' , 'delivery');
    }
    
    public function scopeDelivery($query)
    {
        return $query->where('order_type', '=' , 'delivery');
    }
    
    public function scopeScheduled($query)
    {
        return $query->whereRaw('created_at <> schedule_at')->where('scheduled', 1);
    }
    
    public function scopeOrderScheduledIn($query, $interval)
    {
        return $query->where(function($query)use($interval){
            $query->whereRaw('created_at <> schedule_at')->where(function($q) use ($interval) {
            $q->whereBetween('schedule_at', [Carbon::now()->toDateTimeString(),Carbon::now()->addMinutes($interval)->toDateTimeString()]); 
            })->orWhere('schedule_at','<',Carbon::now()->toDateTimeString());
        })->orWhereRaw('created_at = schedule_at');
        
    }

    public function scopePos($query)
    {
        return $query->where('order_type', '=' , 'pos');
    }

    public function scopeNotpos($query)
    {
        return $query->where('order_type', '<>' , 'pos');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s',strtotime($value));
    }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }
}
