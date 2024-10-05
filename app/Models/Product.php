<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('product_name' , 'like' , '%' . request('search') . '%')
                ->orwhere('product_id' , 'like' , '%' . request('search') . '%')
                ->orwhere('store_name', 'like' , '%' . request('search') . '%')
                ->orwhere('created_at', 'like' ,'%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'product_id',
        'product_name',
        'product_quantity',
        'product_price',
        'store_name',
        'description',
        'product_image',
        'status',
        'level',
    ];

    public static function single($id){
        $products = self::all();

        foreach($products as $product){
            if($product['id'] == $id){
                return $product;
            }
        }
    }
}
