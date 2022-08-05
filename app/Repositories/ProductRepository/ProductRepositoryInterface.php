<?php

namespace App\Repositories\ProductRepository;

use App\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllProducts($request);

}
