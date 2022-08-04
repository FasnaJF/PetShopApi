<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create a new payment
     * @OA\Post (
     *     path="/api/v1/payment/create",
     *     operationId="payments-create",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "type", "details"
     *                  },
     *                  @OA\Property(
     *                      property = "type",
     *                      type = "string",
     *                      description = "Payment type",
     *                      enum = {"credit_card","cash_on_delivery","bank_transfer"}
     *                  ),
     *                  @OA\Property(
     *                      property = "details",
     *                      type = "object",
     *                      description = "Review documentation for the payment type JSON format"
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
    public function create(CreatePaymentRequest $request)
    {
        $paymentDetails = $request->validated();
        $paymentDetails['details'] =json_decode($paymentDetails['details']);
        $paymentDetails['uuid'] = Str::uuid();
        $payment = $this->paymentService->createPayment($paymentDetails);
        return $this->returnResource(new PaymentResource($payment));
    }

    /**
     * Update an existing payment
     * @OA\Put (
     *     path="/api/v1/payment/{uuid}",
     *     operationId="payments-update",
     *     tags={"Payments"},
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
     *                            "type"
     *                  },
     *                  @OA\Property(
     *                      property = "type",
     *                      type = "string",
     *                      description = "Payment type",
     *                      enum = {"credit_card","cash_on_delivery","bank_transfer"}
     *                  ),
     *                  @OA\Property(
     *                      property = "details",
     *                      type = "object",
     *                      description = "Review documentation for the payment type JSON format"
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
    public function update(UpdatePaymentRequest $request)
    {
        $paymentDetails = $request->validated();
        $payment = $this->paymentService->getPaymentByUUID($request->uuid);
        $paymentDetails['details'] =json_decode($paymentDetails['details']);

        if ($payment) {
            $updatedPayment = $this->paymentService->updatePayment($payment->id, $paymentDetails);
            return $this->returnResource(new PaymentResource($updatedPayment));
        } else {
            return $this->resourceNotFound("Payment not found");
        }
    }

    /**
     * delete an existing payment
     * @OA\Delete (
     *     path="/api/v1/payment/{uuid}",
     *     operationId="payments-delete",
     *     tags={"Payments"},
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
        $payment = $this->paymentService->getPaymentByUUID($request->uuid);
        if (!$payment) {
            return $this->resourceNotFound("Payment not found");
        }

        if ($this->paymentService->deletePayment($payment->id)) {
            return $this->emptySuccessResponse();
        }
    }

    /**
     * Fetch a payment
     * @OA\Get (
     *     path="/api/v1/payment/{uuid}",
     *     operationId="payments-read",
     *     tags={"Payments"},
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
        $payment = $this->paymentService->getPaymentByUUID($request->uuid);
        if (!$payment) {
            return $this->resourceNotFound("Payment not found");
        }
        return $this->returnResource(new PaymentResource($payment));
    }

    /**
     * List all payments
     * @OA\Get (
     *     path="/api/v1/payments",
     *     operationId="payments-listing",
     *     tags={"Payments"},
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
    public function index(Request $request)
    {
        $page = $request->input('page');
        $limit = $request->input('limit');
        $sortBy = $request->input('sortBy');
        $desc = $request->input('desc');
        return PaymentResource::collection($this->paymentService->getAllPayments());
    }
}
