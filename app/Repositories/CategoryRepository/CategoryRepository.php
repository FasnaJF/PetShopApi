<?php

namespace App\Repositories\CategoryRepository;

use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getAllCategories($request)
    {
        return $this->getAllWithQueryParams($request);
    }

}
