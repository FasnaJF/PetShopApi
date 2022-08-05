<?php

namespace App\Services;

use App\Repositories\CategoryRepository\CategoryRepositoryInterface;

class CategoryService
{
    private CategoryRepositoryInterface $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepo->getById($id);
    }

    public function getCategoryByUUID($uuid)
    {
        return $this->categoryRepo->getByUUID($uuid);
    }

    public function createCategory($data)
    {
        return $this->categoryRepo->create($data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepo->deleteById($id);
    }

    public function getCategoryByEmail($email)
    {
        return $this->categoryRepo->getByEmail($email);
    }

    public function updateCategory($id, $data)
    {
        return $this->categoryRepo->updateById($id, $data);
    }

    public function getAllCategories($request)
    {
        return $this->categoryRepo->getAllCategories($request);
    }
}
