<?php

namespace App\Repositories\UserRepository;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getByEmail($email);

    public function getAllUsers($request);

}
