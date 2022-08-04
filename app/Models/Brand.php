<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Brand extends Model
{
    use HasFactory;
    use HasJsonRelationships;

    private BigInteger $id;

    public function products()
    {
        return $this->hasMany(Product::class, 'metadata->brand', 'uuid');
    }
}
