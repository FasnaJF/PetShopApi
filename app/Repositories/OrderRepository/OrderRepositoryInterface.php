<?php

namespace App\Repositories\OrderRepository;

use App\Repositories\BaseRepositoryInterface;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getOrdersForUser($userId);

}
