<?php

namespace App\Services;

use App\Repositories\BrandRepository\BrandRepositoryInterface;

class BrandService
{
    private BrandRepositoryInterface $brandRepo;

    public function __construct(BrandRepositoryInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    public function getBrandById($id)
    {
        return $this->brandRepo->getById($id);
    }

    public function createBrand($data)
    {
        return $this->brandRepo->create($data);
    }

    public function deleteBrand($id)
    {
        return $this->brandRepo->deleteById($id);
    }

    public function getBrandByEmail($email)
    {
        return $this->brandRepo->getByEmail($email);
    }

    public function updateBrand($id, $data)
    {
        return $this->brandRepo->updateById($id, $data);
    }

    public function getBrandByUUID($uuid)
    {
        return $this->brandRepo->getByUUID($uuid);
    }

    public function getAllBrands()
    {
        return $this->brandRepo->getAll();
    }
}
