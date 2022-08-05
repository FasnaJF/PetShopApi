<?php

namespace App\Repositories\ProductRepository;

use App\Repositories\BaseRepository;
use App\Models\Product;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function getAllProducts($request)
    {
        return $this->getAllWithQueryParams($request);
    }

}
