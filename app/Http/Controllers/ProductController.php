<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Create a new product
     * @OA\Post (
     *     path="/api/v1/product/create",
     *     operationId="products-create",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "category_uuid","title","price","description","metadata"
     *                  },
     *                  @OA\Property(
     *                      property = "category_uuid",
     *                      type = "string",
     *                      description = "Category UUID"
     *                  ),
     *                  @OA\Property(
     *                      property = "title",
     *                      type = "string",
     *                      description = "Product title"
     *                  ),
     *                  @OA\Property(
     *                      property = "price",
     *                      type = "number",
     *                      description = "Product price"
     *                  ),
     *                  @OA\Property(
     *                      property = "description",
     *                      type = "string",
     *                      description = "Product description"
     *                  ),
     *                  @OA\Property(
     *                      property = "metadata",
     *                      type = "object",
     *                      description = "Product metadata",
     *                     @OA\Property(
     *                      property = "image",
     *                      type = "string"
     *                      ),
     *                     @OA\Property(
     *                      property = "brand",
     *                      type = "string"
     *                     ),
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
    public function create(CreateProductRequest $request)
    {
        $productDetails = $request->validated();
        $productDetails['uuid'] = Str::uuid();
        $productDetails['metadata'] = json_decode($productDetails['metadata']);
        $product = $this->productService->createProduct($productDetails);

        return $this->customResponse(['uuid' => $product->uuid]);
    }

    /**
     * Update an existing product
     * @OA\Put (
     *     path="/api/v1/product/{uuid}",
     *     operationId="products-update",
     *     tags={"Products"},
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
     *                            "category_uuid","title","price","description","metadata"
     *                  },
     *                  @OA\Property(
     *                      property = "category_uuid",
     *                      type = "string",
     *                      description = "Category UUID"
     *                  ),
     *                  @OA\Property(
     *                      property = "title",
     *                      type = "string",
     *                      description = "Product title"
     *                  ),
     *                  @OA\Property(
     *                      property = "price",
     *                      type = "number",
     *                      description = "Product price"
     *                  ),
     *                  @OA\Property(
     *                      property = "description",
     *                      type = "string",
     *                      description = "Product description"
     *                  ),
     *                  @OA\Property(
     *                      property = "metadata",
     *                      type = "object",
     *                      description = "Product metadata",
     *                     @OA\Property(
     *                      property = "image",
     *                      type = "string"
     *                      ),
     *                     @OA\Property(
     *                      property = "brand",
     *                      type = "string"
     *                     ),
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
    public function update(UpdateProductRequest $request)
    {
        $productDetails = $request->validated();
        $productDetails['metadata'] = json_decode($productDetails['metadata']);
        $product = $this->productService->getProductByUUID($request->uuid);

        if ($product) {
            $updatedProduct = $this->productService->updateProduct($product->id, $productDetails);
            return $this->returnResource(new ProductResource($updatedProduct));
        } else {
            return $this->resourceNotFound("Product not found");
        }
    }

    /**
     * delete an existing product
     * @OA\Delete (
     *     path="/api/v1/product/{uuid}",
     *     operationId="products-delete",
     *     tags={"Products"},
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
        $product = $this->productService->getProductByUUID($request->uuid);
        if (!$product) {
            return $this->resourceNotFound("Product not found");
        }

        if ($this->productService->deleteProduct($product->id)) {
            return $this->emptySuccessResponse();
        }
    }

    /**
     * Fetch a product
     * @OA\Get (
     *     path="/api/v1/product/{uuid}",
     *     operationId="products-read",
     *     tags={"Products"},
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
        $product = $this->productService->getProductByUUID($request->uuid);
        if (!$product) {
            return $this->resourceNotFound("Product not found");
        }
        return $this->returnResource(new ProductResource($product));
    }

    /**
     * List all products
     * @OA\Get (
     *     path="/api/v1/products",
     *     operationId="products-listing",
     *     tags={"Products"},
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
        return ProductResource::collection($this->productService->getAllProducts());
    }
}
