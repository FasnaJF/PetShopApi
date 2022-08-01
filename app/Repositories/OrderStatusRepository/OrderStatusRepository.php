<?php

namespace App\Repositories\OrderStatusRepository;

use App\Repositories\BaseRepository;
use App\Models\OrderStatus;

class OrderStatusRepository extends BaseRepository implements OrderStatusRepositoryInterface
{
    public function __construct(OrderStatus $orderStatus)
    {
        $this->model = $orderStatus;
    }

}
