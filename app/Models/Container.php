<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'name',
        'length',
        'width',
        'height',
        'capacity',
        'tare_weight',
        'gross_weight',
        'max_payload',
        'description',
    ];
}
