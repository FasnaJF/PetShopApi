<?php

namespace App\Repositories\OrderRepository;

use App\Models\Order;
use App\Repositories\BaseRepository;
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

    public function getAllOrders($request)
    {
        return $this->getAllWithQueryParams($request);
    }

    public function getAllShippedOrders($request)
    {
        $limit = $request->input('limit') ? $request->input('limit') : null;
        $sortBy = $request->input('sortBy') ? $request->input('sortBy') : 'id';
        $desc = ($request->input('desc') == 'true') ? 'DESC' : 'ASC';
        $sortBy = [$sortBy, $desc];
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
                    ->whereBetween(
                        'shipped_at',
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
            ->when($sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy[0], $sortBy[1]);
            })
            ->paginate($limit);

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

    public function getAllOrdersDashboard($request)
    {
        $limit = $request->input('limit') ? $request->input('limit') : null;
        $sortBy = $request->input('sortBy') ? $request->input('sortBy') : 'id';
        $desc = ($request->input('desc') == 'true') ? 'DESC' : 'ASC';
        $sortBy = [$sortBy, $desc];
        $dateRange = $request->input('dateRange');
        $fixRange = $request->input('fixRange');
        $dateRange = $this->getFixedRangeDate($fixRange);

        $orders = $this->model
            ->when($dateRange, function ($query, $dateRange) {
                return $query
                    ->whereBetween(
                        'created_at',
                        [
                            Carbon::parse($dateRange['from'])->toDatetimeString(),
                            Carbon::parse($dateRange['to'])->toDatetimeString()
                        ]
                    );
            })
            ->when($sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy[0], $sortBy[1]);
            })
            ->paginate($limit);

        return $orders;
    }

}
