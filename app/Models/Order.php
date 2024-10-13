<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('id', 'like' , '%' . request('search') . '%')
            ->orwhere('container_id' , 'like' , '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'staff_name',
        'container_id',
        'product_name',
        'quantity',
    ];

    public static function find($id){
        $orders = self::all();

        foreach ($orders as $order) {
           if($order['id'] == $id){
            return $order;
           }
        }
    }
}
