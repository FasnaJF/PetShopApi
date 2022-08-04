<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderStatusRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderStatusResource;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderStatusController extends Controller
{
    private OrderStatusService $orderStatusService;

    public function __construct(OrderStatusService $orderStatusService)
    {
        $this->orderStatusService = $orderStatusService;
    }

    /**
     * Create a new orderStatus
     * @OA\Post (
     *     path="/api/v1/order-status/create",
     *     operationId="order-statuses-create",
     *     tags={"Order Statuses"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "title"
     *                  },
     *                  @OA\Property(
     *                      property = "title",
     *                      type = "string",
     *                      description = "Order Status title"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     * )
     */
    public function create(CreateOrderStatusRequest $request)
    {
        $orderStatusDetails = $request->validated();
        $orderStatusDetails['uuid'] = Str::uuid();
        $orderStatus = $this->orderStatusService->createOrderStatus($orderStatusDetails);

        return $this->customResponse(['uuid' => $orderStatus->uuid]);
    }

    /**
     * Update an existing orderStatus
     * @OA\Put (
     *     path="/api/v1/order-status/{uuid}",
     *     operationId="order-statuses-update",
     *     tags={"Order Statuses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "title"
     *                  },
     *                  @OA\Property(
     *                      property = "title",
     *                      type = "string",
     *                      description = "Order Status title"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     * )
     */
    public function update(UpdateOrderStatusRequest $request)
    {
        $orderStatus = $this->orderStatusService->getOrderStatusByUUID($request->uuid);

        if ($orderStatus) {
            $updatedOrderStatus = $this->orderStatusService->updateOrderStatus($orderStatus->id, $request->all());
            return $this->returnResource(new OrderStatusResource($updatedOrderStatus));
        } else {
            return $this->resourceNotFound("Order Status not found");
        }
    }

    /**
     * delete an existing orderStatus
     * @OA\Delete (
     *     path="/api/v1/order-status/{uuid}",
     *     operationId="order-statuses-delete",
     *     tags={"Order Statuses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     *
     * )
     */
    public function destroy(Request $request)
    {
        $orderStatus = $this->orderStatusService->getOrderStatusByUUID($request->uuid);
        if (!$orderStatus) {
            return $this->resourceNotFound("Order Status not found");
        }

        if ($this->orderStatusService->deleteOrderStatus($orderStatus->id)) {
            return $this->emptySuccessResponse();
        }
    }

    /**
     * Fetch a orderStatus
     * @OA\Get (
     *     path="/api/v1/order-status/{uuid}",
     *     operationId="order-statuses-read",
     *     tags={"Order Statuses"},
     *     @OA\Parameter(
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     *
     * )
     */
    public function show(Request $request)
    {
        $orderStatus = $this->orderStatusService->getOrderStatusByUUID($request->uuid);
        if (!$orderStatus) {
            return $this->resourceNotFound("Order Status not found");
        }
        return $this->returnResource(new OrderStatusResource($orderStatus));
    }

    /**
     * List all order statuses
     * @OA\Get (
     *     path="/api/v1/order-statuses",
     *     operationId="order-statuses-listing",
     *     tags={"Order Statuses"},
     *     @OA\Parameter(
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="sortBy",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="desc",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $page = $request->input('page');
        $limit = $request->input('limit');
        $sortBy = $request->input('sortBy');
        $desc = $request->input('desc');
        return OrderStatusResource::collection($this->orderStatusService->getAllOrderStatuss());
    }
}
