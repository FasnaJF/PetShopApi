<?php

namespace App\Repositories\UserRepository;

use App\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getByEmail($email)
    {
        return  $this->model->where('email', $email)->first();
    }
    
}
