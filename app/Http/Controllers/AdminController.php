<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Services\JwtTokenService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private JwtTokenService $jwtTokenService;
    private UserService $userService;

    public function __construct(JwtTokenService $jwtTokenService, UserService $userService)
    {
        $this->jwtTokenService = $jwtTokenService;
        $this->userService = $userService;
    }

    /**
     * Create an Admin account
     * @OA\Post (
     *     path="/api/v1/admin/create",
     *     operationId="admin-create",
     *     tags={"Admin"},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "first_name","last_name","email","password","password_confirmation","avatar","address","phone_number"
     *                  },
     *                  @OA\Property(
     *                      property = "first_name",
     *                      type = "string",
     *                      description = "User firstname"
     *                  ),
     *                  @OA\Property(
     *                      property = "last_name",
     *                      type = "string",
     *                      description = "User lastname"
     *                  ),
     *                  @OA\Property(
     *                      property = "email",
     *                      type = "string",
     *                      description = "User email"
     *                  ),
     *                  @OA\Property(
     *                      property = "password",
     *                      type = "string",
     *                      description = "User password"
     *                  ),
     *                  @OA\Property(
     *                      property = "password_confirmation",
     *                      type = "string",
     *                      description = "User password"
     *                  ),
     *                  @OA\Property(
     *                      property = "avatar",
     *                      type = "string",
     *                      description = "Avatar image UUID"
     *                  ),
     *                  @OA\Property(
     *                      property = "address",
     *                      type = "string",
     *                      description = "User main address"
     *                  ),
     *                  @OA\Property(
     *                      property = "phone_number",
     *                      type = "string",
     *                      description = "User main phone number"
     *                  ),
     *                  @OA\Property(
     *                      property = "marketing",
     *                      type = "string",
     *                      description = "User marketing preferences"
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
    public function register(CreateUserRequest $request): UserResource
    {
        $userDetails = $request->validated();
        $userDetails['uuid'] = Str::uuid();
        $userDetails['password'] = Hash::make($request->password);
        $userDetails['is_admin'] = 1;
        $user = $this->userService->createUser($userDetails);

        if ($user) {
            $this->jwtTokenService->createJwtToken(
                ['user_id' => $user->id, 'unique_id' => $this->createJwtToken($user), 'token_title' => 'create_admin']
            );
        }

        return (new UserResource($user))
            ->withSuccess(1)
            ->withError(null)
            ->withErrors([])
            ->withExtra([]);
    }

    /**
     * Login an Admin account
     * @OA\Post (
     *     path="/api/v1/admin/login",
     *     operationId="admin-login",
     *     tags={"Admin"},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "email","password"
     *                  },
     *                  @OA\Property(
     *                      property = "email",
     *                      type = "string",
     *                      description = "Admin email"
     *                  ),
     *                  @OA\Property(
     *                      property = "password",
     *                      type = "string",
     *                      description = "Admin password"
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
    public function login(LoginRequest $request): BaseResource
    {
        $user = $this->userService->getUserByEmail($request->email);
        if (!$user) {
            return $this->unprocessableEntityResponse( "Failed to authenticate user");
        }

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $jwtToken = $this->createJwtToken($user);
            $this->jwtTokenService->createJwtToken(
                ['user_id' => $user->id, 'unique_id' => $jwtToken, 'token_title' => 'admin_login']
            );

            return $this->customResponse(['token' => $jwtToken]);

        } else {
            return $this->unprocessableEntityResponse( "Failed to authenticate user");
        }
    }

    /**
     * Logout an Admin account
     * @OA\Get (
     *     path="/api/v1/admin/logout",
     *     operationId="admin-logout",
     *     tags={"Admin"},
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
    public function logout(): BaseResource
    {
        Auth::logout();
        return $this->emptySuccessResponse();
    }

    /**
     * Edit a User account
     * @OA\Put (
     *     path="/api/v1/admin/user-edit/{uuid}",
     *     operationId="admin-user-edit",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "first_name","last_name","email","password","password_confirmation","address","phone_number"
     *                  },
     *                  @OA\Property(
     *                      property = "first_name",
     *                      type = "string",
     *                      description = "User firstname"
     *                  ),
     *                  @OA\Property(
     *                      property = "last_name",
     *                      type = "string",
     *                      description = "User lastname"
     *                  ),
     *                  @OA\Property(
     *                      property = "email",
     *                      type = "string",
     *                      description = "User email"
     *                  ),
     *                  @OA\Property(
     *                      property = "password",
     *                      type = "string",
     *                      description = "User password"
     *                  ),
     *                  @OA\Property(
     *                      property = "password_confirmation",
     *                      type = "string",
     *                      description = "User password"
     *                  ),
     *                  @OA\Property(
     *                      property = "avatar",
     *                      type = "string",
     *                      description = "Avatar image UUID"
     *                  ),
     *                  @OA\Property(
     *                      property = "address",
     *                      type = "string",
     *                      description = "User main address"
     *                  ),
     *                  @OA\Property(
     *                      property = "phone_number",
     *                      type = "string",
     *                      description = "User main phone number"
     *                  ),
     *                  @OA\Property(
     *                      property = "marketing",
     *                      type = "string",
     *                      description = "User marketing preferences"
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
    public function updateUser(EditUserRequest $request)
    {
        $user = $this->userService->getUserByUUID($request->uuid);
        if ($user->is_admin === 1) {
            return $this->unprocessableEntityResponse("Unauthorized: Not enough privileges");
        }
        if ($user) {
            $updatedUser = $this->userService->updateUser($user->id, $request->all());
            return (new UserResource($updatedUser))
                ->withSuccess(1)
                ->withError(null)
                ->withErrors([])
                ->withExtra([]);
        } else {
            return $this->resourceNotFound("User not found");
        }
    }

    /**
     * Delete a User account
     * @OA\Delete (
     *     path="/api/v1/admin/user-delete/{uuid}",
     *     operationId="admin-user-delete",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     security={{"bearerAuth":{}}},
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
    public function destroyUser(Request $request)
    {
        $user = $this->userService->getUserByUUID($request->uuid);
        if (!$user) {
            return $this->resourceNotFound("User not found");
        }
        if ($user->is_admin === 1) {
            return $this->unprocessableEntityResponse("Unauthorized: Not enough privileges");
        }
        if ($this->userService->deleteUser($user->id)) {
            return $this->emptySuccessResponse();
        }
    }

    /**
     * List all users
     * @OA\Get (
     *     path="/api/v1/admin/user-listing",
     *     operationId="admin-user-listing",
     *     tags={"Admin"},
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
     *         name="first_name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="phone",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="address",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="created_at",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="marketing",
     *         required=false,
     *         @OA\Schema(type="string", enum={"0", "1"})
     *     ),
     *     security={{"bearerAuth":{}}},
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
    public function listAllUsers()
    {
        return UserResource::collection($this->userService->getAllUsers());
    }
}
