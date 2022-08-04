<?php

namespace App\Repositories\CategoryRepository;

use App\Repositories\BaseRepository;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getAll($sortBy = null)
    {
        return $this->model->paginate(10);
    }

}
