<?php

namespace App\Repositories\PaymentRepository;

use App\Repositories\BaseRepository;
use App\Models\Payment;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $payment)
    {
        $this->model = $payment;
    }

    public function getAll($sortBy = null)
    {
        return $this->model->paginate(10);
    }

}
