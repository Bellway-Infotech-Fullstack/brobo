<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $casts = [
        'question' => 'string',
        'answer' => 'string',
    ];
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    
}
