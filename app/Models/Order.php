<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Order extends Model
{
    use HasFactory;
    use HasJsonRelationships;


    protected $casts = [
        'products' => 'json',
        'address' => 'json',
        'shipped_at' =>'datetime'
    ];

    protected $fillable = [
        'user_id',
        'order_status_id',
        'payment_id',
        'uuid',
        'products',
        'address',
        'delivery_fee',
        'amount',
        'shipped_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'uuid');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'uuid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'products->uuid','uuid');
    }

}
