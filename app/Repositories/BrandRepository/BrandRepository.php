<?php

namespace App\Repositories\BrandRepository;

use App\Models\Brand;
use App\Repositories\BaseRepository;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $brand)
    {
        $this->model = $brand;
    }

    public function getAllBrands($request)
    {
        return $this->getAllWithQueryParams($request);
    }

}
