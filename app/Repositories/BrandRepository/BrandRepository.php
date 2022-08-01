<?php

namespace App\Repositories\BrandRepository;

use App\Repositories\BaseRepository;
use App\Models\Brand;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $brand)
    {
        $this->model = $brand;
    }

}
