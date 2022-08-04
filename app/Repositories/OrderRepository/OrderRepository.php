<?php

namespace App\Repositories\OrderRepository;

use App\Repositories\BaseRepository;
use App\Models\Order;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $order)
    {
        $this->model = $order;
    }


    public function getOrdersForUser($userId)
    {
        return $this->model->where('user_id',$userId)->paginate();
    }

}
