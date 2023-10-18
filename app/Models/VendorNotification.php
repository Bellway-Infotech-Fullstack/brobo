<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorNotification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title','message','is_seen','vendor_id'
    ];

}
