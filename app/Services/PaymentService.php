<?php

namespace App\Services;

use App\Repositories\PaymentRepository\PaymentRepositoryInterface;

class PaymentService
{
    private PaymentRepositoryInterface $paymentRepo;

    public function __construct(PaymentRepositoryInterface $paymentRepo)
    {
        $this->paymentRepo = $paymentRepo;
    }

    public function getPaymentById($id)
    {
        return $this->paymentRepo->getById($id);
    }

    public function createPayment($data)
    {
        return $this->paymentRepo->create($data);
    }

    public function deletePayment($id)
    {
        return $this->paymentRepo->deleteById($id);
    }

    public function getPaymentByEmail($email)
    {
        return $this->paymentRepo->getByEmail($email);
    }

    public function updatePayment($id, $data)
    {
        return $this->paymentRepo->updateById($id, $data);
    }

    public function getPaymentByUUID($uuid)
    {
        return $this->paymentRepo->getByUUID($uuid);
    }

    public function getAllPayments()
    {
        return $this->paymentRepo->getAll();
    }
}
