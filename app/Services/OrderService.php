<?php

namespace App\Services;

use App\Repositories\OrderRepository\OrderRepositoryInterface;

class OrderService
{
    private OrderRepositoryInterface $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function getOrderById($id)
    {
        return $this->orderRepo->getById($id);
    }

    public function createOrder($data)
    {
        return $this->orderRepo->create($data);
    }

    public function deleteOrder($id)
    {
        return $this->orderRepo->deleteById($id);
    }

    public function getOrderByEmail($email)
    {
        return $this->orderRepo->getByEmail($email);
    }

    public function updateOrder($id, $data)
    {
        return $this->orderRepo->updateById($id, $data);
    }

    public function getOrdersByUserId($userId)
    {
        return $this->orderRepo->getOrdersForUser($userId);
    }

    public function getOrderByUUID($uuid)
    {
        return $this->orderRepo->getByUUID($uuid);
    }

    public function getAllOrders($request)
    {
        return $this->orderRepo->getAllOrders($request);
    }

    public function getAllShippedOrders($request)
    {
        return $this->orderRepo->getAllShippedOrders($request);
    }

    public function getAllOrdersDashboard($request)
    {
        return $this->orderRepo->getAllOrdersDashboard($request);
    }


}
