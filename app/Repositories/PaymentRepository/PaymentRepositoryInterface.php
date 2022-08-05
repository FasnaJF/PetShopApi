<?php

namespace App\Repositories\PaymentRepository;

use App\Repositories\BaseRepositoryInterface;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllPayments($request);

}
