<?php

namespace App\Services;

use App\Repositories\JwtTokenRepository\JwtTokenRepositoryInterface;

class JwtTokenService
{
    private JwtTokenRepositoryInterface $jwtTokenRepo;

    public function __construct(JwtTokenRepositoryInterface $jwtTokenRepo)
    {
        $this->jwtTokenRepo = $jwtTokenRepo;
    }

    public function getJwtTokenById($id)
    {
        return $this->jwtTokenRepo->getById($id);
    }

    public function createJwtToken($data)
    {
        return $this->jwtTokenRepo->create($data);
    }

    public function deleteJwtToken($id)
    {
        return $this->jwtTokenRepo->deleteById($id);
    }

    public function getJwtTokenByEmail($email)
    {
        return $this->jwtTokenRepo->getByEmail($email);
    }

    public function updateJwtToken($id, $data)
    {
        return $this->jwtTokenRepo->updateById($id, $data);
    }
}
