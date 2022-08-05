<?php

namespace App\Repositories\OrderRepository;

use App\Repositories\BaseRepository;
use App\Models\Order;
use Carbon\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $order)
    {
        $this->model = $order;
    }


    public function getOrdersForUser($userId)
    {
        return $this->model->where('user_id', $userId)->paginate();
    }

    public function getAll($sortBy = null)
    {
        return $this->model->paginate(10);
    }

    public function getAllShippedOrders($request)
    {
        $page = $request->input('page');
        $limit = $request->input('limit');
        $sortBy = $request->input('sortBy');
        $desc = $request->input('desc');
        $orderUuid = $request->input('orderUuid');
        $customerUuid = $request->input('customerUuid');
        $dateRange = $request->input('dateRange');
        $fixRange = $request->input('fixRange');
        $dateRange = $this->getFixedRangeDate($fixRange);

        $orders = $this->model
            ->when($orderUuid, function ($query, $orderUuid) {
                return $query->where('uuid', $orderUuid);
            })
            ->when($dateRange, function ($query, $dateRange) {
                return $query
                    ->whereBetween('shipped_at',
                        [
                            Carbon::parse($dateRange['from'])->toDatetimeString(),
                            Carbon::parse($dateRange['to'])->toDatetimeString()
                        ]
                    );
            })
            ->when($customerUuid, function ($query, $customerUuid) {
                return $query->where('user_id', $customerUuid);
            })
            ->whereNotNull('shipped_at')
            ->paginate();

        return $orders;
    }

    public function getAllOrdersDashboard($request)
    {
        $page = $request->input('page');
        $limit = $request->input('limit');
        $sortBy = $request->input('sortBy');
        $desc = $request->input('desc');
        $dateRange = $request->input('dateRange');
        $fixRange = $request->input('fixRange');
        $dateRange = $this->getFixedRangeDate($fixRange);

        $orders = $this->model
            ->when($dateRange, function ($query, $dateRange) {
                return $query
                    ->whereBetween('created_at',
                        [
                            Carbon::parse($dateRange['from'])->toDatetimeString(),
                            Carbon::parse($dateRange['to'])->toDatetimeString()
                        ]
                    );
            })
            ->paginate();

        return $orders;
    }

    protected function getFixedRangeDate($fixRange)
    {
        switch ($fixRange) {
            case 'today':
                $dateRange['from'] = Carbon::now()->startOfDay()->toDateTimeString();
                $dateRange['to'] = Carbon::now()->toDateTimeString();
                break;
            case 'monthly':
                $dateRange['from'] = Carbon::now()->startOfMonth()->toDateTimeString();
                $dateRange['to'] = Carbon::now()->endOfMonth()->toDateTimeString();
                break;
            case 'yearly':
                $dateRange['from'] = Carbon::now()->startOfYear()->toDateTimeString();
                $dateRange['to'] = Carbon::now()->endOfYear()->toDateTimeString();
                break;
            default:
                $dateRange['from'] = Carbon::now()->startOfDay()->toDateTimeString();
                $dateRange['to'] = Carbon::now()->toDateTimeString();
        }
        return $dateRange;
    }

}
