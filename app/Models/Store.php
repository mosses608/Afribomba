<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('store_name' , 'like' , '%' . request('search') . '%')
            ->orwhere('store_id' , 'like' , '%' . request('search') . '%')
            ->orwhere('store_location' , 'like' , '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'store_id',
        'store_name',
        'store_location',
    ];
}
