<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'quantity_in_stock',
        'price_per_unit',
    ];

    public function orders(): BelongsToMany{
        return $this->belongsToMany(Order::class);
    }


}
