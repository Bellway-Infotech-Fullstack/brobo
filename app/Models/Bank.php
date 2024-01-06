<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    protected $table = 'banks';

    protected $fillable = ['account_number', 'ifsc_code','bank_name', 'customer_id'];

    protected $casts = [
        'account_number' => 'string',
        'ifsc_code' => 'string',
        'bank_name' => 'string',
        'customer_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
