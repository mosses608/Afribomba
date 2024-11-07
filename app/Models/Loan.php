<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('customer_name', 'like', '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'customer_name',
        'tin',
        'phone',
        'product_name',
        'staff_name',
        'product_quantity',
        'product_price',
        'payment_date',
        'status',
        'amount_paid',
    ];
}
