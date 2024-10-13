<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('created_at', 'like' , '%' . request('search') . '%')
                ->orwhere('id', 'like', '%' . request('search') . '%')
                ->orwhere('tin', 'like' , '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        // 'product_id',
        'tin',
        'product_name',
        'customer_name',
        'staff_name',
        'staff_name',
        'product_quantity',
        'product_price',
        'product_image',
        'phone',
        'sale_mode',
        'payment_date',
        'status',
    ];
}
