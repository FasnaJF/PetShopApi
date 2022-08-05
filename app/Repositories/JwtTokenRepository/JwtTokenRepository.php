<?php

namespace App\Repositories\JwtTokenRepository;

use App\Models\JwtToken;
use App\Repositories\BaseRepository;

class JwtTokenRepository extends BaseRepository implements JwtTokenRepositoryInterface
{
    public function __construct(JwtToken $jwtToken)
    {
        $this->model = $jwtToken;
    }

}
