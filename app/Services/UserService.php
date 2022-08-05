<?php

namespace App\Services;

use App\Repositories\UserRepository\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getUserById($id)
    {
        return $this->userRepo->getById($id);
    }

    public function createUser($data)
    {
        return $this->userRepo->create($data);
    }

    public function deleteUser($id)
    {
        return $this->userRepo->deleteById($id);
    }

    public function getUserByEmail($email)
    {
        return $this->userRepo->getByEmail($email);
    }

    public function updateUser($id, $data)
    {
        $user =  $this->userRepo->updateById($id, $data);
        if($user){
            return $this->getUserById($user->id);
        }
        return false;
    }

    public function getAllUsers($request)
    {
        return $this->userRepo->getAllUsers($request);
    }

    public function getUserByUUID($uuid)
    {
        return $this->userRepo->getByUUID($uuid);
    }
}
