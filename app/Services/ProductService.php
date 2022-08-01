<?php

namespace App\Services;

use App\Repositories\ProductRepository\ProductRepositoryInterface;

class ProductService
{
    private ProductRepositoryInterface $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getProductById($id)
    {
        return $this->productRepo->getById($id);
    }

    public function createProduct($data)
    {
        return $this->productRepo->create($data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepo->deleteById($id);
    }

    public function getProductByEmail($email)
    {
        return $this->productRepo->getByEmail($email);
    }

    public function updateProduct($id, $data)
    {
        return $this->productRepo->updateById($id, $data);
    }
}
