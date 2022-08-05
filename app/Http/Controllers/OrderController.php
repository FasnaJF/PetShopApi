<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use App\Services\ProductService;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\isJson;

class OrderController extends Controller
{
    private OrderService $orderService;
    private ProductService $productService;

    public function __construct(OrderService $orderService, ProductService $productService)
    {
        $this->orderService = $orderService;
        $this->productService = $productService;
    }

//    /**
//     * Create a new order
//     * @OA\Post (
//     *     path="/api/v1/order/create",
//     *     operationId="orders-create",
//     *     tags={"Orders"},
//     *     security={{"bearerAuth":{}}},
//     *      @OA\RequestBody(
//     *          required = true,
//     *          @OA\MediaType(
//     *              mediaType = "application/x-www-form-urlencoded",
//     *              @OA\Schema(
//     *                  type = "object",
//     *                  required={
//     *                            "order_status_uuid","payment_uuid","products","address"
//     *                  },
//     *                  @OA\Property(
//     *                      property = "order_status_uuid",
//     *                      type = "string",
//     *                      description = "Order status UUID"
//     *                  ),
//     *                  @OA\Property(
//     *                      property = "payment_uuid",
//     *                      type = "string",
//     *                      description = "Payment UUID"
//     *                  ),
//     *                  @OA\Property(
//     *                      property = "products",
//     *                      type = "array",
//     *                      description = "Array of objects with product uuid and quantity",
//     *                      @OA\Items(
//     *                          type= "object",
//     *                          @OA\Property(
//     *                              property = "uuid",
//     *                              type = "string",
//     *                              description = "Product UUID"
//     *                          ),
//     *                          @OA\Property(
//     *                              property = "quantity",
//     *                              type = "string",
//     *                              description = "Product Quantity"
//     *                          ),
//     *                      ),
//     *                  ),
//     *                  @OA\Property(
//     *                      property = "address",
//     *                      type = "object",
//     *                      description = "Billing and Shipping address",
//     *                      @OA\Property(
//     *                          property = "billing",
//     *                          type = "string",
//     *                      ),
//     *                      @OA\Property(
//     *                          property = "shipping",
//     *                          type = "string",
//     *                      ),
//     *                  ),
//     *              )
//     *          )
//     *      ),
//     *     @OA\Response(
//     *         response=200,
//     *         description="OK",
//     *     ),
//     *      @OA\Response(
//     *         response=401,
//     *         description="Unauthorized",
//     *     ),
//     *     @OA\Response(
//     *         response=404,
//     *         description="Page not found",
//     *     ),
//     *      @OA\Response(
//     *         response=422,
//     *         description="Unprocessable Entity",
//     *     ),
//     *      @OA\Response(
//     *         response=500,
//     *         description="Internal server error",
//     *     ),
//     * )
//     */
//    public function create(CreateOrderRequest $request)
//    {
//        $orderDetails = $request->validated();
//        $orderDetails['uuid'] = Str::uuid();
//        $orderDetails['user_id'] = Auth::user()->uuid;
//        $orderDetails['order_status_id'] = $orderDetails['order_status_uuid'];
//        $orderDetails['payment_id'] = $orderDetails['payment_uuid'];
//        $orderDetails['address'] = json_decode($orderDetails['address']);
//        $orderDetails['amount'] = 0;
//        $orderDetails['delivery_fee'] = 0;
//
//        $order = $this->orderService->createOrder($orderDetails);
//        return $this->customResponse(['uuid' => $order->uuid]);
//    }
//
//    /**
//     * Update an existing order
//     * @OA\Put (
//     *     path="/api/v1/order/{uuid}",
//     *     operationId="orders-update",
//     *     tags={"Orders"},
//     *     security={{"bearerAuth":{}}},
//     *     @OA\Parameter(
//     *         in="path",
//     *         name="uuid",
//     *         required=true,
//     *         @OA\Schema(type="string")
//     *     ),
//     *      @OA\RequestBody(
//     *          required = true,
//     *          @OA\MediaType(
//     *              mediaType = "application/x-www-form-urlencoded",
//     *              @OA\Schema(
//     *                  type = "object",
//     *                  required={
//     *                            "order_status_uuid","payment_uuid","products","address"
//     *                  },
//     *                  @OA\Property(
//     *                      property = "order_status_uuid",
//     *                      type = "string",
//     *                      description = "Order status UUID"
//     *                  ),
//     *                  @OA\Property(
//     *                      property = "payment_uuid",
//     *                      type = "string",
//     *                      description = "Payment UUID"
//     *                  ),
//     *                  @OA\Property(
//     *                      property = "products",
//     *                      type = "array",
//     *                      description = "Array of objects with product uuid and quantity",
//     *                      @OA\Items(
//     *                          type= "object",
//     *                          @OA\Property(
//     *                              property = "uuid",
//     *                              type = "string",
//     *                              description = "Product UUID"
//     *                          ),
//     *                          @OA\Property(
//     *                              property = "quantity",
//     *                              type = "string",
//     *                              description = "Product Quantity"
//     *                          ),
//     *                      ),
//     *                  ),
//     *                  @OA\Property(
//     *                      property = "address",
//     *                      type = "object",
//     *                      description = "Billing and Shipping address",
//     *                      @OA\Property(
//     *                          property = "billing",
//     *                          type = "string",
//     *                      ),
//     *                      @OA\Property(
//     *                          property = "shipping",
//     *                          type = "string",
//     *                      ),
//     *                  ),
//     *              )
//     *          )
//     *      ),
//     *     @OA\Response(
//     *         response=404,
//     *         description="Page not found",
//     *     ),
//     *      @OA\Response(
//     *         response=422,
//     *         description="Unprocessable Entity",
//     *     ),
//     *      @OA\Response(
//     *         response=500,
//     *         description="Internal server error",
//     *     ),
//     * )
//     */
//    public function update(UpdateOrderRequest $request)
//    {
//        $order = $this->orderService->getOrderByUUID($request->uuid);
//
//        if ($order) {
//            $updatedOrder = $this->orderService->updateOrder($order->id, $request->all());
//            return $this->returnResource(new OrderResource($updatedOrder));
//        } else {
//            return $this->resourceNotFound("Order not found");
//        }
//    }

    /**
     * delete an existing order
     * @OA\Delete (
     *     path="/api/v1/order/{uuid}",
     *     operationId="orders-delete",
     *     tags={"Orders"},
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
        $order = $this->orderService->getOrderByUUID($request->uuid);
        if (!$order) {
            return $this->resourceNotFound("Order not found");
        }

        if ($this->orderService->deleteOrder($order->id)) {
            return $this->emptySuccessResponse();
        }
    }

    /**
     * Fetch an order
     * @OA\Get (
     *     path="/api/v1/order/{uuid}",
     *     operationId="orders-read",
     *     tags={"Orders"},
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
    public function show(Request $request)
    {
        $order = $this->orderService->getOrderByUUID($request->uuid);
        if (!$order) {
            return $this->resourceNotFound("Order not found");
        }
        return $this->returnResource(new OrderResource($order));
    }

    /**
     * List all orders
     * @OA\Get (
     *     path="/api/v1/orders",
     *     operationId="orders-listing",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
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
    public function index(GetOrdersRequest $request)
    {
        return OrderResource::collection($this->orderService->getAllOrders($request));
    }

    /**
     * List all shipped orders
     * @OA\Get (
     *     path="/api/v1/orders/shipment-locator",
     *     operationId="orders-shipping-listing",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
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
     *     @OA\Parameter(
     *         in="query",
     *         name="orderUuid",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="customerUuid",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="dateRange",
     *         required=false,
     *         style="deepObject",
     *         explode="true",
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="from",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="to",
     *                  type="string"
     *              ),
     *          ),
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="fixRange",
     *         required=false,
     *         @OA\Schema(
     *              type="string",
     *              enum = {"today","monthly","yearly"}
     *          ),
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
    public function shipmentLocator(GetOrdersRequest $request)
    {
        return OrderResource::collection($this->orderService->getAllShippedOrders($request));
    }

    /**
     * List all orders to populate the dashboard
     * @OA\Get (
     *     path="/api/v1/orders/dashboard",
     *     operationId="orders-dashboard-listing",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
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
     *     @OA\Parameter(
     *         in="query",
     *         name="dateRange",
     *         required=false,
     *         style="deepObject",
     *         explode="true",
     *         @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="from",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="to",
     *                  type="string"
     *              ),
     *          ),
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="fixRange",
     *         required=false,
     *         @OA\Schema(
     *              type="string",
     *              enum = {"today","monthly","yearly"}
     *          ),
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
    public function dashboard(GetOrdersRequest $request)
    {
        return OrderResource::collection($this->orderService->getAllOrdersDashboard($request));
    }

    /**
     * Download an order
     * @OA\Get (
     *     path="/api/v1/order/{uuid}/download",
     *     operationId="orders-download",
     *     tags={"Orders"},
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
    public function downloadOrder(Request $request)
    {
        $order = $this->orderService->getOrderByUUID($request->uuid);
        if (!$order) {
            return $this->resourceNotFound("Order not found");
        }
        $orderProducts = [];
        $i= 1;
        $amount = 0;

        foreach ($order->products as $orderProduct) {
            $product = Product::where('uuid', $orderProduct['product'])->select('title', 'price')->first();
            $orderProductDetail['id'] = $i;
            $orderProductDetail['uuid'] = $orderProduct['product'];
            $orderProductDetail['price'] = $product->price;
            $orderProductDetail['product'] = $product->title;
            $orderProductDetail['quantity'] = $orderProduct['quantity'];
            $orderProducts[] = $orderProductDetail;
            $amount += $orderProduct['quantity'] * $product->price;
            $i++;
        }

        $order = $this->orderService->getOrderByUUID($request->uuid);
        $order['order_products'] = $orderProducts;
        $order['amount'] = $amount;
        $order['delivery_fee'] = ($amount < 500)? 15 : 0;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('order_details', compact('order'));

        return $pdf->download($order->uuid . '.pdf');
    }
}
