<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersAddress extends Model
{
    protected $table = 'users_addresses';    
        
    protected $fillable = ['house_name','floor_number','landmark','zip_code','address_id','customer_id','area_name'];
    use HasFactory;
}
