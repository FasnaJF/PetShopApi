<?php

namespace App\Repositories\PaymentRepository;

use App\Models\Payment;
use App\Repositories\BaseRepository;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $payment)
    {
        $this->model = $payment;
    }

    public function getAllPayments($request)
    {
        return $this->getAllWithQueryParams($request);
    }

}
