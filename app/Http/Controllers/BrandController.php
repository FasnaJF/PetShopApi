<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Services\BrandService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    private BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * Create a new brand
     * @OA\Post (
     *     path="/api/v1/brand/create",
     *     operationId="brands-create",
     *     tags={"Brands"},
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
     *                      description = "Brand title"
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
    public function create(CreateBrandRequest $request)
    {
        $brandDetails = $request->validated();
        $brandDetails['uuid'] = Str::uuid();
        $brandDetails['slug'] = Str::slug($brandDetails['title']);
        $brand = $this->brandService->createBrand($brandDetails);

        return $this->customResponse(['uuid' => $brand->uuid]);
    }

    /**
     * Update an existing brand
     * @OA\Put (
     *     path="/api/v1/brand/{uuid}",
     *     operationId="brands-update",
     *     tags={"Brands"},
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
     *                      description = "Brand title"
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
    public function update(UpdateBrandRequest $request)
    {
        $brand = $this->brandService->getBrandByUUID($request->uuid);

        if ($brand) {
            $updatedBrand = $this->brandService->updateBrand($brand->id, $request->all());
            return $this->returnResource(new BrandResource($updatedBrand));
        } else {
            return $this->resourceNotFound("Brand not found");
        }
    }

    /**
     * delete an existing brand
     * @OA\Delete (
     *     path="/api/v1/brand/{uuid}",
     *     operationId="brands-delete",
     *     tags={"Brands"},
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
        $brand = $this->brandService->getBrandByUUID($request->uuid);
        if (!$brand) {
            return $this->resourceNotFound("Brand not found");
        }

        if ($this->brandService->deleteBrand($brand->id)) {
            return $this->emptySuccessResponse();
        }
    }

    /**
     * Fetch a brand
     * @OA\Get (
     *     path="/api/v1/brand/{uuid}",
     *     operationId="brands-read",
     *     tags={"Brands"},
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
        $brand = $this->brandService->getBrandByUUID($request->uuid);
        if (!$brand) {
            return $this->resourceNotFound("Brand not found");
        }
        return $this->returnResource(new BrandResource($brand));
    }

    /**
     * List all brands
     * @OA\Get (
     *     path="/api/v1/brands",
     *     operationId="brands-listing",
     *     tags={"Brands"},
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
        return BrandResource::collection($this->brandService->getAllBrands($request));
    }

}
