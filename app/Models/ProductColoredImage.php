<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use App\Scopes\VendorScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductColoredImage extends Model
{
    protected $table = 'product_colored_image';

    protected $casts = [
        'color_name' => 'string',
        'image' => 'string',
        'product_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
