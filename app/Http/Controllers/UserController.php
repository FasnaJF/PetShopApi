<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\ResetPasswordTokenRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Services\JwtTokenService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private JwtTokenService $jwtTokenService;
    private UserService $userService;
    private OrderService $orderService;

    public function __construct(JwtTokenService $jwtTokenService, UserService $userService, OrderService $orderService)
    {
        $this->jwtTokenService = $jwtTokenService;
        $this->userService = $userService;
        $this->orderService = $orderService;
    }

    /**
     * View a User account
     * @OA\Get (
     *     path="/api/v1/user",
     *     operationId="user-read",
     *     tags={"User"},
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
     * )
     */
    public function getUser()
    {
        $user = Auth::user();
        return $this->returnResource(new UserResource($user));
    }

    /**
     * Delete a User account
     * @OA\Delete (
     *     path="/api/v1/user",
     *     operationId="user-delete",
     *     tags={"User"},
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
    public function destroy()
    {
        $user = $this->userService->getUserById(Auth::user()->id);
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
     * List all orders for the user
     * @OA\Get (
     *     path="/api/v1/user/orders",
     *     operationId="user-orders-listing",
     *     tags={"User"},
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
     * )
     */
    public function getUserOrders()
    {
        $user = Auth::user();
        return OrderResource::collection($this->orderService->getOrdersByUserId($user->id));
    }

    /**
     * Update a User account
     * @OA\Put (
     *     path="/api/v1/user/edit",
     *     operationId="user-update",
     *     tags={"User"},
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
        $user = $this->userService->getUserById(Auth::user()->id);
        if ($user->is_admin === 1) {
            return $this->unprocessableEntityResponse("Unauthorized: Not enough privileges");
        }
        if ($user) {
            $updatedUser = $this->userService->updateUser($user->id, $request->all());
            return $this->returnResource(new UserResource($updatedUser));
        } else {
            return $this->resourceNotFound("User not found");
        }
    }

    /**
     * Login an User account
     * @OA\Post (
     *     path="/api/v1/user/login",
     *     operationId="user-login",
     *     tags={"User"},
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
     *                      description = "User email"
     *                  ),
     *                  @OA\Property(
     *                      property = "password",
     *                      type = "string",
     *                      description = "User password"
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
            return $this->unprocessableEntityResponse("Failed to authenticate user");
        }

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $jwtToken = $this->createJwtToken($user);
            $this->jwtTokenService->createJwtToken(
                ['user_id' => $user->id, 'unique_id' => $jwtToken, 'token_title' => 'user_login']
            );
            return $this->customResponse(['token' => $jwtToken]);
        } else {
            return $this->unprocessableEntityResponse("Failed to authenticate user");
        }
    }

    /**
     * Logout an User account
     * @OA\Get (
     *     path="/api/v1/user/logout",
     *     operationId="user-logout",
     *     tags={"User"},
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
     * Create a User account
     * @OA\Post (
     *     path="/api/v1/user/create",
     *     operationId="user-create",
     *     tags={"User"},
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
    public function createUser(CreateUserRequest $request): UserResource
    {
        $userDetails = $request->validated();
        $userDetails['uuid'] = Str::uuid();
        $userDetails['password'] = Hash::make($request->password);
        $user = $this->userService->createUser($userDetails);

        if ($user) {
            $this->jwtTokenService->createJwtToken(
                ['user_id' => $user->id, 'unique_id' => $this->createJwtToken($user), 'token_title' => 'create_user']
            );
        }
        return $this->returnResource(new UserResource($user));
    }

    /**
     * Creates a token to reset a user password
     * @OA\Post (
     *     path="/api/v1/user/forgot-password",
     *     operationId="user-forgot-pass",
     *     tags={"User"},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "email"
     *                  },
     *                  @OA\Property(
     *                      property = "email",
     *                      type = "string",
     *                      description = "User email"
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
    public function forgotPassword(ForgotPasswordRequest $request): BaseResource
    {
        $user = $this->userService->getUserByEmail($request->email);
        if (!$user) {
            return $this->resourceNotFound('Invalid email');
        }
        $token = Password::createToken($user);
        return $this->customResponse(['reset_token' => $token]);
    }

    /**
     * Reset a user password with the token
     * @OA\Post (
     *     path="/api/v1/user/reset-password-token",
     *     operationId="user-reset-pass-token",
     *     tags={"User"},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "token","email","password","password_confirmation"
     *                  },
     *                  @OA\Property(
     *                      property = "token",
     *                      type = "string",
     *                      description = "User reset token"
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
    public function resetPasswordToken(ResetPasswordTokenRequest $request): BaseResource
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return $this->customResponse(['message' => 'Password has been successfully updated']);
        } else {
            return $this->unprocessableEntityResponse("Invalid or expired token");
        }
    }


}
