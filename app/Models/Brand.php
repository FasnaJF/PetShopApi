<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * @OA\Schema(
 *     title="Brand",
 *     description="Brand model",
 *     @OA\Xml(
 *         name="Brand"
 *     )
 * )
 */
class Brand extends Model
{
    use HasFactory;
    use HasJsonRelationships;

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var bigInteger
     */
    private BigInteger $id;

    public function products()
    {
        return $this->hasMany(Product::class, 'metadata->brand', 'uuid');
    }
}
