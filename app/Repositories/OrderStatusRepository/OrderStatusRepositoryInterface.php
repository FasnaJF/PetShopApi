<?php

namespace App\Repositories\OrderStatusRepository;

use App\Repositories\BaseRepositoryInterface;

interface OrderStatusRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllOrderStatuses($request);

}
