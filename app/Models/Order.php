<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
      'customer_name',
      'customer_phone',
      'status',
      'user_id'
    ];

    public function users():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        // return $this->hasMany(Product::class,'id','product_id');
        return $this->belongsToMany(Product::class)
        ->withPivot(['quantity_product_order', 'price','total','total_all','deleted_at','created_at','updated_at'])->withTimestamps();
    }
}
