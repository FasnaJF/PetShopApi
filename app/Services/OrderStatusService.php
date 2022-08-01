<?php

namespace App\Services;

use App\Repositories\OrderStatusRepository\OrderStatusRepositoryInterface;

class OrderStatusService
{
    private OrderStatusRepositoryInterface $orderStatusRepo;

    public function __construct(OrderStatusRepositoryInterface $orderStatusRepo)
    {
        $this->orderStatusRepo = $orderStatusRepo;
    }

    public function getOrderStatusById($id)
    {
        return $this->orderStatusRepo->getById($id);
    }

    public function createOrderStatus($data)
    {
        return $this->orderStatusRepo->create($data);
    }

    public function deleteOrderStatus($id)
    {
        return $this->orderStatusRepo->deleteById($id);
    }

    public function getOrderStatusByEmail($email)
    {
        return $this->orderStatusRepo->getByEmail($email);
    }

    public function updateOrderStatus($id, $data)
    {
        return $this->orderStatusRepo->updateById($id, $data);
    }
}
