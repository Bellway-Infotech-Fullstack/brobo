<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['item_id', 'quantity', 'customer_id'];

    protected $casts = [
        'item_id' => 'integer',
        'quantity' => 'integer',
        'customer_id' => 'integer'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
}
