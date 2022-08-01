<?php

namespace App\Repositories\UserRepository;

use App\Repository\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getByEmail($email);

}
