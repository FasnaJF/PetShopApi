<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Services\JwtTokenService;
use App\Services\UserService;
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

    public function login(LoginRequest $request): BaseResource
    {
        $user = $this->userService->getUserByEmail($request->email);
        if (!$user) {
            return (new BaseResource([]))
                ->withSuccess(0)
                ->withError("Failed to authenticate user")
                ->withErrors([])
                ->withExtra([]);
        }

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $jwtToken = $this->createJwtToken($user);
            $this->jwtTokenService->createJwtToken(
                ['user_id' => $user->id, 'unique_id' => $jwtToken, 'token_title' => 'admin_login']
            );
            return (new BaseResource(['token' => $jwtToken]))
                ->withSuccess(1)
                ->withError(null)
                ->withErrors([])
                ->withExtra([]);
        } else {
            return (new BaseResource([]))
                ->withSuccess(0)
                ->withError("Failed to authenticate user")
                ->withErrors([])
                ->withExtra([]);
        }
    }

    public function logout(): BaseResource
    {
        Auth::logout();
        return (new BaseResource([]))
            ->withSuccess(1)
            ->withError(null)
            ->withErrors([])
            ->withExtra([]);
    }

    public function listAllUsers()
    {
        return UserResource::collection($this->userService->getAllUsers());
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $user = $this->userService->getUserByUUID($request->uuid);

        if ($user) {
            $updatedUser = $this->userService->updateUser($user->id, $request->all());
            return (new UserResource($updatedUser))
                ->withSuccess(1)
                ->withError(null)
                ->withErrors([])
                ->withExtra([]);
        } else {
            return (new BaseResource([]))
                ->withSuccess(0)
                ->withError("User not found")
                ->withErrors([])
                ->withExtra([]);
        }
    }

    public function destroyUser(UpdateUserRequest $request)
    {
        $user = $this->userService->getUserByUUID($request->uuid);

        if($this->userService->deleteUser($user->id)){
            return (new BaseResource([]))
                ->withSuccess(1)
                ->withError(null)
                ->withErrors([])
                ->withExtra([]);
        }

    }
}
