<?php

namespace App\Repositories\OrderStatusRepository;

use App\Models\OrderStatus;
use App\Repositories\BaseRepository;

class OrderStatusRepository extends BaseRepository implements OrderStatusRepositoryInterface
{
    public function __construct(OrderStatus $orderStatus)
    {
        $this->model = $orderStatus;
    }

    public function getAllOrderStatuses($request)
    {
        return $this->getAllWithQueryParams($request);
    }
}
