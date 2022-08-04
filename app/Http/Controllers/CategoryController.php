<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Create a new category
     * @OA\Post (
     *     path="/api/v1/category/create",
     *     operationId="categories-create",
     *     tags={"Categories"},
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
     *                      description = "Category title"
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
    public function create(CreateCategoryRequest $request)
    {
        $categoryDetails = $request->validated();
        $categoryDetails['uuid'] = Str::uuid();
        $categoryDetails['slug'] = Str::slug($categoryDetails['title']);
        $category = $this->categoryService->createCategory($categoryDetails);

        return $this->customResponse(['uuid' => $category->uuid]);
    }

    /**
     * Update an existing category
     * @OA\Put (
     *     path="/api/v1/category/{uuid}",
     *     operationId="categories-update",
     *     tags={"Categories"},
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
     *                      description = "Category title"
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
    public function update(UpdateCategoryRequest $request)
    {
        $category = $this->categoryService->getCategoryByUUID($request->uuid);

        if ($category) {
            $updatedCategory = $this->categoryService->updateCategory($category->id, $request->all());
            return $this->returnResource(new CategoryResource($updatedCategory));
        } else {
            return $this->resourceNotFound("Category not found");
        }
    }

    /**
     * delete an existing category
     * @OA\Delete (
     *     path="/api/v1/category/{uuid}",
     *     operationId="categories-delete",
     *     tags={"Categories"},
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
        $category = $this->categoryService->getCategoryByUUID($request->uuid);
        if (!$category) {
            return $this->resourceNotFound("Category not found");
        }

        if ($this->categoryService->deleteCategory($category->id)) {
            return $this->emptySuccessResponse();
        }
    }

    /**
     * Fetch a category
     * @OA\Get (
     *     path="/api/v1/category/{uuid}",
     *     operationId="categories-read",
     *     tags={"Categories"},
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
        $category = $this->categoryService->getCategoryByUUID($request->uuid);
        if (!$category) {
            return $this->resourceNotFound("Category not found");
        }
        return $this->returnResource(new CategoryResource($category));
    }

    /**
     * List all categories
     * @OA\Get (
     *     path="/api/v1/categories",
     *     operationId="categories-listing",
     *     tags={"Categories"},
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
        return CategoryResource::collection($this->categoryService->getAllCategories());
    }

}
