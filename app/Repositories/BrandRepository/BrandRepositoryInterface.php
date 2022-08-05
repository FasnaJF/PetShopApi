<?php

namespace App\Repositories\BrandRepository;

use App\Repositories\BaseRepositoryInterface;

interface BrandRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllBrands($request);
}
